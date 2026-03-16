<?php
namespace App\Services;

use App\Models\Persona;
use App\Models\HistorialAcademico;
use App\Models\Periodo;
use Config\Database;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;
use PDOException;

class ImportService {
    private $pdo;
    
    // Diccionario para traducir texto a IDs (Sincronizado con tu tabla tipos_personas)
    private $mapaComunidad = [
        'estudiante'     => 1,
        'docente'        => 2,
        'administrativo' => 3,
        'egresado'       => 4,
        'invitado'       => 5
    ];

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function procesarArchivo($rutaArchivo) {
        $stats = [
            'procesados' => 0,
            'nuevos' => 0, 
            'actualizados' => 0, // Lo dejamos en 0 porque ahora omitiremos
            'omitidos' => 0,     // NUEVO: Contador de omitidos
            'errores' => [] 
        ];

        try {
            // 1. CARGA DEL ARCHIVO
            try {
                $spreadsheet = IOFactory::load($rutaArchivo);
                $hoja = $spreadsheet->getActiveSheet();
                $filas = $hoja->toArray();
            } catch (Exception $e) {
                return ['error_fatal' => "El archivo no se puede leer o está corrupto."];
            }

            // 2. VALIDACIÓN DE ENTORNO
            $periodoModel = new Periodo();
            $idPeriodo = $periodoModel->getActivoId();
            if (!$idPeriodo) return ['error_fatal' => "No hay un periodo académico activo configurado en el sistema."];

            // Ignorar la fila de encabezados
            array_shift($filas); 

            // 3. PROCESAMIENTO FILA POR FILA
            foreach ($filas as $index => $col) {
                $filaNum = $index + 2;
                $stats['procesados']++;

                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }

                $this->pdo->beginTransaction();

                try {
                                    // A. SANITIZACIÓN DE DATOS EXTREMA (Data Hygiene)
                                    $tipoDoc   = $this->sanitizarTexto($col[0] ?? 'CC');
                                    $doc       = $this->limpiarDocumento($col[1] ?? ''); 
                                    $nombres   = strtoupper($this->sanitizarTexto($col[2] ?? ''));
                                    $apellidos = strtoupper($this->sanitizarTexto($col[3] ?? ''));
                                    $correo    = strtolower($this->sanitizarTexto($col[4] ?? ''));
                                    $comunidad = strtolower($this->sanitizarTexto($col[5] ?? 'Estudiante'));
                                    
                                    // Corrección para celdas vacías en Excel evaluadas como 0
                                    $valorProg = trim($col[6] ?? '');
                                    $idProg    = ($valorProg === '') ? 99 : (int)$valorProg; 
                                    
                                    $nivel     = $this->sanitizarTexto($col[7] ?? 'Tecnólogo');
                                    // 🔥 NUEVO: Protección contra niveles inválidos en el Excel
                                                $nivelesValidos = ['Técnico', 'Tecnólogo', 'Profesional', 'Postgrado', 'Especializacion'];
                                                if (!in_array($nivel, $nivelesValidos)) {
                                                    $nivel = 'Tecnólogo'; // Valor por defecto si escribieron algo raro en el Excel
                                                }

                                    // Validaciones básicas
                                    if (empty($doc) && empty($nombres)) {
                                        $this->pdo->rollBack();
                                        continue; 
                                    }

                                    if (empty($doc)) {
                                        $stats['omitidos']++;
                                        $stats['errores'][] = "Fila $filaNum: Documento vacío. (Omitido)";
                                        $this->pdo->rollBack();
                                        continue; 
                                    }

                                    $idTipoPersona = $this->mapaComunidad[$comunidad] ?? 5;

                                    // B. GESTIÓN DE DUPLICADOS
                                    $personaModel = new Persona();
                                    $persona = $personaModel->getByDocumento($doc);

                                    $esNuevo = false; // Bandera para saber qué contar al final

                                    if ($persona) {
                                        // SI YA EXISTE -> LO OMITIMOS
                                        $stats['omitidos']++;
                                        $stats['errores'][] = "Fila $filaNum: El documento $doc ya existe en el sistema. (Omitido)";
                                        $this->pdo->rollBack();
                                        continue; 
                                    } else {
                                        // SI ES NUEVO -> LO CREAMOS EN LA TRANSACCIÓN
                                        $idPersona = $personaModel->create([
                                            'tipo_documento' => $tipoDoc,
                                            'numero_documento' => $doc,
                                            'nombres' => $nombres,
                                            'apellidos' => $apellidos,
                                            'correo_institucional' => $correo,
                                            'id_tipo_persona' => $idTipoPersona
                                        ]);
                                        $esNuevo = true; // Marcamos que es nuevo, pero AÚN NO SUMAMOS
                                    }

                                    // C. GESTIÓN DE HISTORIAL ACADÉMICO
                                    if (!$this->existePrograma($idProg)) {
                                        $idProg = 99; // Si pusieron un ID raro, lo mandamos a "Invitado"
                                    }

                                    $historial = new HistorialAcademico();
                                    $historial->guardarOActualizar([
                                        'persona_id' => $idPersona,
                                        'periodo_id' => $idPeriodo,
                                        'id_programa' => $idProg,
                                        'id_tipo_persona' => $idTipoPersona, 
                                        'nivel_formacion' => $nivel,
                                        'semestre_cursado' => 0
                                    ]);

                                    // 🔥 SI LLEGAMOS AQUÍ, NADA FALLÓ. GUARDAMOS EN BASE DE DATOS.
                                    $this->pdo->commit();

                                    // 🔥 Y AHORA SÍ, ACTUALIZAMOS LOS CONTADORES (Porque ya es oficial)
                                    if ($esNuevo) {
                                        $stats['nuevos']++;
                                    }

                                } catch (Exception $e) {
                                    if ($this->pdo->inTransaction()) {
                                        $this->pdo->rollBack();
                                    }
                                    $stats['omitidos']++; // Como falló, lo contamos como omitido
                                    $stats['errores'][] = "Fila $filaNum ($doc): " . $this->traducirError($e->getMessage());
                                }
                            }

                            return $stats;

                        } catch (Exception $e) {
                            return ['error_fatal' => "Error crítico del sistema: " . $e->getMessage()];
                        }
                    }

                    // --- MÉTODOS DE HIGIENE DE DATOS (NIVEL SENIOR) ---

                    private function sanitizarTexto($valor) {
                        if ($valor === null) return '';
                        $valor = (string)$valor;
                        // 1. Convertir todo a UTF-8 real
                        $valor = mb_convert_encoding($valor, 'UTF-8', 'auto');
                        // 2. Eliminar "Non-breaking spaces" ocultos de Excel (\xC2\xA0)
                        $valor = str_replace("\xC2\xA0", ' ', $valor);
                        // 3. Eliminar caracteres de control o espacios de ancho cero
                        $valor = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $valor);
                        // 4. Reducir múltiples espacios consecutivos a uno solo
                        $valor = preg_replace('/\s+/', ' ', $valor);
                        return trim($valor);
                    }

                    private function limpiarDocumento($doc) {
                        $doc = $this->sanitizarTexto($doc);
                        // Expresión Regular: Mantiene SOLO letras y números. 
                        // Si en el Excel escriben "1.090.456-A", esto lo convierte en "1090456A".
                        return preg_replace('/[^a-zA-Z0-9]/', '', $doc);
                    }

    // --- MÉTODOS AUXILIARES ---

    private function existePrograma($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM programas WHERE id_programa = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    private function traducirError($msg) {
        if (strpos($msg, '1048') !== false) return "Falta un dato obligatorio en la fila.";
        if (strpos($msg, '1062') !== false) return "Registro duplicado en la base de datos.";
        // 🔥 CORREGIDO: El 1452 puede ser por la persona, el programa o el periodo
        if (strpos($msg, '1452') !== false) return "Error de integridad: El programa, rol o periodo no coinciden en BD.";
        if (strpos($msg, '1265') !== false) return "Dato truncado: Un campo de texto (como Nivel) excede el límite permitido.";
        return $msg;
    }
}
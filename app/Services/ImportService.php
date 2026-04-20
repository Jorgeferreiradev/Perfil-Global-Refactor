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
            'actualizados' => 0, 
            'omitidos' => 0,     
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

            // 🔥 3. MURO DE HIERRO: VALIDACIÓN ESTRICTA DE LA PLANTILLA
            if (empty($filas) || count($filas) < 2) {
                return ['error_fatal' => "El archivo está vacío o no tiene datos para procesar."];
            }

            // Analizamos la fila 1 (Cabeceras)
            $cabeceras = array_map(function($val) { 
                return strtolower(trim((string)$val)); 
            }, $filas[0]);

            $esPlantillaValida = false;
            foreach ($cabeceras as $cabecera) {
                // Si la primera fila NO dice documento, identificacion o cédula, es el archivo equivocado.
                if (strpos($cabecera, 'documento') !== false || strpos($cabecera, 'identificaci') !== false || strpos($cabecera, 'cédula') !== false) {
                    $esPlantillaValida = true;
                    break;
                }
            }

            if (!$esPlantillaValida) {
                return ['error_fatal' => "❌ ALERTA DE SEGURIDAD: Estás intentando subir un archivo que NO es la plantilla oficial de Personas (ej. Matriz Semestral o Reporte). Operación abortada para evitar corrupción de datos."];
            }

            // Si pasó el muro, ignoramos la fila de encabezados y seguimos
            array_shift($filas); 

            // 4. PROCESAMIENTO FILA POR FILA
            foreach ($filas as $index => $col) {
                $filaNum = $index + 2;
                

                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }

                $this->pdo->beginTransaction();

                try {
                    // SANITIZACIÓN DE DATOS EXTREMA
                    $tipoDoc   = $this->sanitizarTexto($col[0] ?? 'CC');
                    $doc       = $this->limpiarDocumento($col[1] ?? ''); 
                    $nombres   = strtoupper($this->sanitizarTexto($col[2] ?? ''));
                    $apellidos = strtoupper($this->sanitizarTexto($col[3] ?? ''));
                    $correo    = strtolower($this->sanitizarTexto($col[4] ?? ''));
                    $comunidad = strtolower($this->sanitizarTexto($col[5] ?? 'Estudiante'));
                    
                    // 🔥 LÓGICA SENIOR: Si la fila está totalmente en blanco (artefactos de Excel), paramos el ciclo.
                    if (empty($doc) && empty($nombres) && empty($apellidos) && empty($correo)) {
                        $this->pdo->rollBack();
                        break; 
                    }

                    $stats['procesados']++;

                    $valorProg = trim($col[6] ?? '');
                    $idProg    = ($valorProg === '') ? 99 : (int)$valorProg; 
                    
                    $nivel     = $this->sanitizarTexto($col[7] ?? 'Tecnólogo');
                    $nivelesValidos = ['Técnico', 'Tecnólogo', 'Profesional', 'Postgrado', 'Especializacion'];
                    if (!in_array($nivel, $nivelesValidos)) {
                        $nivel = 'Tecnólogo';
                    }
                    
                    $celular   = preg_replace('/[^0-9]/', '', $this->sanitizarTexto($col[8] ?? ''));

                    // Validaciones básicas
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

                    $esNuevo = false;

                    if ($persona) {
                        $stats['omitidos']++;
                        $stats['errores'][] = "Fila $filaNum: El documento $doc ya existe en el sistema. (Omitido)";
                        $this->pdo->rollBack();
                        continue; 
                    } else {
                        $idPersona = $personaModel->create([
                            'tipo_documento' => $tipoDoc,
                            'numero_documento' => $doc,
                            'nombres' => $nombres,
                            'apellidos' => $apellidos,
                            'correo_institucional' => $correo,
                            'telefono' => !empty($celular) ? $celular : null,
                            'id_tipo_persona' => $idTipoPersona
                        ]);
                        $esNuevo = true; 
                    }

                    // C. GESTIÓN DE HISTORIAL ACADÉMICO
                    if (!$this->existePrograma($idProg)) {
                        $idProg = 99;
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

                    $this->pdo->commit();

                    if ($esNuevo) {
                        $stats['nuevos']++;
                    }

                } catch (Exception $e) {
                    if ($this->pdo->inTransaction()) {
                        $this->pdo->rollBack();
                    }
                    $stats['omitidos']++;
                    $stats['errores'][] = "Fila $filaNum ($doc): " . $this->traducirError($e->getMessage());
                }
            }

            return $stats;

        } catch (Exception $e) {
            return ['error_fatal' => "Error crítico del sistema: " . $e->getMessage()];
        }
    }

    // --- MÉTODOS DE HIGIENE DE DATOS ---

    private function sanitizarTexto($valor) {
        if ($valor === null) return '';
        $valor = (string)$valor;
        $valor = mb_convert_encoding($valor, 'UTF-8', 'auto');
        $valor = str_replace("\xC2\xA0", ' ', $valor);
        $valor = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $valor);
        $valor = preg_replace('/\s+/', ' ', $valor);
        return trim($valor);
    }

    private function limpiarDocumento($doc) {
        $doc = $this->sanitizarTexto($doc);
        return preg_replace('/[^a-zA-Z0-9]/', '', $doc);
    }

    private function existePrograma($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM programas WHERE id_programa = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    private function traducirError($msg) {
        if (strpos($msg, '1048') !== false) return "Falta un dato obligatorio en la fila.";
        if (strpos($msg, '1062') !== false) return "Registro duplicado en la base de datos.";
        if (strpos($msg, '1452') !== false) return "Error de integridad: El programa, rol o periodo no coinciden en BD.";
        if (strpos($msg, '1265') !== false) return "Dato truncado: Un campo excede el límite permitido.";
        return $msg;
    }
}
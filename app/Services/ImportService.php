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
            'actualizados' => 0, 
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

                // --- PROTECCIÓN DE TRANSACCIÓN ---
                // Si por alguna razón extraña quedó una transacción abierta, la cerramos antes de empezar.
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }

                // Iniciamos la transacción solo para ESTA fila
                $this->pdo->beginTransaction();

                try {
                    // A. SANITIZACIÓN DE DATOS
                    $tipoDoc   = $this->sanitizar($col[0] ?? 'CC');
                    $doc       = $this->sanitizar($col[1] ?? '');
                    $nombres   = $this->sanitizar($col[2] ?? '');
                    $apellidos = $this->sanitizar($col[3] ?? '');
                    $correo    = strtolower($this->sanitizar($col[4] ?? ''));
                    $comunidad = strtolower($this->sanitizar($col[5] ?? 'Estudiante'));
                    $idProg    = (int)($col[6] ?? 99);
                    $nivel     = $this->sanitizar($col[7] ?? 'Tecnólogo');

                    // Validaciones básicas
                    if (empty($doc)) {
                        // Si no hay documento, cancelamos transacción silenciosamente y seguimos
                        $this->pdo->rollBack();
                        continue; 
                    }

                    // Mapeo seguro del ID Tipo Persona (Comunidad)
                    $idTipoPersona = $this->mapaComunidad[$comunidad] ?? 5; // 5 = Invitado/Externo

                    // B. GESTIÓN DE PERSONA
                    $personaModel = new Persona();
                    $persona = $personaModel->getByDocumento($doc);

                    if ($persona) {
                        $personaModel->update($persona['id'], [
                            'nombres' => $nombres,
                            'apellidos' => $apellidos,
                            'correo_institucional' => $correo,
                            'id_tipo_persona' => $idTipoPersona
                        ]);
                        $idPersona = $persona['id'];
                        $stats['actualizados']++;
                    } else {
                        $idPersona = $personaModel->create([
                            'tipo_documento' => $tipoDoc,
                            'numero_documento' => $doc,
                            'nombres' => $nombres,
                            'apellidos' => $apellidos,
                            'correo_institucional' => $correo,
                            'id_tipo_persona' => $idTipoPersona
                        ]);
                        $stats['nuevos']++;
                    }

                    // C. GESTIÓN DE HISTORIAL ACADÉMICO
                    // Validamos que el programa exista (si no, asignamos 99)
                    if (!$this->existePrograma($idProg)) {
                        $idProg = 99;
                    }

                    $historial = new HistorialAcademico();
                    // Usamos guardarOActualizar para evitar duplicados en el mismo periodo
                    $historial->guardarOActualizar([
                        'persona_id' => $idPersona,
                        'periodo_id' => $idPeriodo,
                        'id_programa' => $idProg,
                        'id_tipo_persona' => $idTipoPersona, // Usamos la columna correcta según tu SQL
                        'nivel_formacion' => $nivel,
                        'semestre_cursado' => 0
                    ]);

                    // ÉXITO PARA ESTA FILA
                    $this->pdo->commit();

                } catch (Exception $e) {
                    // ERROR EN ESTA FILA
                    if ($this->pdo->inTransaction()) {
                        $this->pdo->rollBack();
                    }
                    // Registramos el error en español y seguimos con la siguiente fila
                    $stats['errores'][] = "Fila $filaNum ($doc): " . $this->traducirError($e->getMessage());
                }
            }

            return $stats;

        } catch (Exception $e) {
            return ['error_fatal' => "Error crítico del sistema: " . $e->getMessage()];
        }
    }

    // --- MÉTODOS AUXILIARES ---

    private function sanitizar($valor) {
        return trim(mb_convert_encoding($valor ?? '', 'UTF-8', 'auto'));
    }

    private function existePrograma($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM programas WHERE id_programa = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    private function traducirError($msg) {
        if (strpos($msg, '1048') !== false) return "Falta un dato obligatorio (posiblemente Tipo de Persona o Documento).";
        if (strpos($msg, '1062') !== false) return "Registro duplicado en la base de datos.";
        if (strpos($msg, '1452') !== false) return "Error de referencia: El programa o tipo de persona no existe en la base de datos.";
        return $msg;
    }
}
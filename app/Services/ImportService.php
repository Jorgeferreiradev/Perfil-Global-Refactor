<?php
namespace App\Services;

use Config\Database;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Persona;
use App\Models\HistorialAcademico;
use App\Models\Periodo;
use PDO;
use Exception;

class ImportService {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function procesarArchivo($rutaArchivo) {
        $stats = ['nuevos' => 0, 'actualizados' => 0, 'errores' => []];
        
        // 1. Cargar el archivo con PhpSpreadsheet
        try {
            $spreadsheet = IOFactory::load($rutaArchivo);
            $hoja = $spreadsheet->getActiveSheet();
            $filas = $hoja->toArray();
        } catch (Exception $e) {
            return ['error_fatal' => "No se pudo leer el archivo: " . $e->getMessage()];
        }

        // 2. Obtener el Periodo Académico Activo (Ej: 2026-1)
        $periodoModel = new Periodo();
        $idPeriodoActivo = $periodoModel->getActivoId();

        if (!$idPeriodoActivo) {
            return ['error_fatal' => "No hay un periodo académico abierto. Configúralo primero."];
        }

        // 3. Iniciar Transacción (Todo o Nada)
        // Nota: En cargas muy grandes (5k+ registros), se recomienda hacer commits por lotes.
        // Para este nivel, haremos una transacción global por seguridad.
        $this->pdo->beginTransaction();

        try {
            // Ignoramos la primera fila (Encabezados)
            array_shift($filas); 

            foreach ($filas as $index => $columna) {
                // Mapeo según tu CSV:
                // 0:Documento, 1:Nombres, 2:Apellidos, 3:Correo, 4:Comunidad, 5:ID_Prog, 6:Semestre, 7:Nivel
                
                // Validación básica de fila vacía
                if (empty($columna[0])) continue;

                $dataPersona = [
                    'documento' => trim($columna[0]),
                    'nombres'   => trim($columna[1]),
                    'apellidos' => trim($columna[2]),
                    'correo'    => trim($columna[3]),
                    'comunidad' => trim($columna[4]) // Estudiante, Docente, etc.
                ];

                // A. Gestionar Persona (Upsert: Crear o Actualizar)
                $personaModel = new Persona();
                $persona = $personaModel->getByDocumento($dataPersona['documento']);
                
                if ($persona) {
                    $personaModel->update($persona['id'], $dataPersona);
                    $idPersona = $persona['id'];
                    $stats['actualizados']++;
                } else {
                    $idPersona = $personaModel->create($dataPersona);
                    $stats['nuevos']++;
                }

                // B. Registrar Historial Académico (Solo si tiene programa)
                // Columna 5 es ID_Prog. Si es 99 (Administrativo/Invitado), quizás no registramos historial académico o lo manejamos diferente.
                $idPrograma = (int)$columna[5];
                
                if ($idPrograma > 0) {
                    $historialModel = new HistorialAcademico();
                    // Verificar si ya tiene historial en este periodo para no duplicar
                    if (!$historialModel->existeEnPeriodo($idPersona, $idPeriodoActivo)) {
                        $historialModel->create([
                            'persona_id'  => $idPersona,
                            'periodo_id'  => $idPeriodoActivo,
                            'id_programa' => $idPrograma,
                            'semestre'    => (int)$columna[6]
                        ]);
                    }
                }
            }

            $this->pdo->commit();
            return $stats;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['error_fatal' => "Error en la fila " . ($index + 2) . ": " . $e->getMessage()];
        }
    }
}
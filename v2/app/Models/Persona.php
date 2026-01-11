<?php
namespace App\Models;

use Config\Database;
use PDO;

class Persona {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Importa masivamente desde un array de Excel
     * @param array $datos
     * @return bool
     */
    public function importarMasivo($datos) {
        try {
            $this->db->beginTransaction();

            // 1. Query para PERSONAS
            // Usamos LAST_INSERT_ID(id) para que lastInsertId() funcione incluso en duplicados
            $sqlPersona = "INSERT INTO personas (numero_documento, nombres, apellidos, correo_institucional, comunidad) 
                           VALUES (:doc, :nom, :ape, :cor, :com)
                           ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), comunidad=VALUES(comunidad)";
            
            // 2. Query para HISTORIAL_ACADEMICO
            $sqlHistorial = "INSERT INTO historial_academico 
                             (persona_id, periodo_id, id_programa, id_tipo_asistente, nivel_formacion, semestre_cursado)
                             VALUES (:p_id, :per_id, :prog_id, :tipo_id, :nivel, :sem)
                             ON DUPLICATE KEY UPDATE 
                                id_programa=VALUES(id_programa), 
                                semestre_cursado=VALUES(semestre_cursado),
                                nivel_formacion=VALUES(nivel_formacion)";

            $stmtP = $this->db->prepare($sqlPersona);
            $stmtH = $this->db->prepare($sqlHistorial);

            foreach ($datos as $index => $col) {
                // Saltamos encabezado o filas donde el documento esté vacío
                if ($index === 0 || empty(trim($col[0]))) continue;

                // Limpieza de datos (Senior Tip: Excel suele traer espacios invisibles)
                $comunidad = ucfirst(strtolower(trim($col[4] ?? 'Estudiante')));

                // Paso 1: Insertar/Actualizar Persona
                $stmtP->execute([
                    ':doc' => trim($col[0]), // Columna A
                    ':nom' => trim($col[1]), // Columna B
                    ':ape' => trim($col[2]), // Columna C
                    ':cor' => trim($col[3] ?? null), // Columna D
                    ':com' => $comunidad     // Columna E
                ]);
                
                $personaId = $this->db->lastInsertId();

                // Paso 2: Mapear Comunidad a ID_Tipo_Asistente
                $tipoAsistenteId = $this->mapearTipo($comunidad);

                // Paso 3: Insertar en Historial Académico
                $stmtH->execute([
                    ':p_id'    => $personaId,
                    ':per_id'  => 1,                 // Periodo activo (ej: 2025-I)
                    ':prog_id' => (int)($col[5] ?? 6), // Columna F: ID Programa (6=Software)
                    ':tipo_id' => $tipoAsistenteId,
                    ':nivel'   => $col[6] ?? 'Tecnólogo', // Columna G
                    ':sem'     => $col[7] ?? null    // Columna H
                ]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            // Es vital lanzar la excepción para que el Controlador la atrape y muestre el error
            throw new \Exception("Error en fila " . ($index + 1) . ": " . $e->getMessage());
        }
    }

    /**
     * Mapea el texto de comunidad a los IDs de tu base de datos
     */
    private function mapearTipo($comunidad) {
        return match($comunidad) {
            'Estudiante'     => 1,
            'Docente'        => 2,
            'Administrativo' => 3,
            'Egresado'       => 4,
            'Invitado'       => 5,
            default          => 5,
        };
    }

/**
 * Cuenta el total de personas activas en el sistema
 */
    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM personas WHERE deleted_at IS NULL";
        $stmt = $this->db->query($sql);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }


}
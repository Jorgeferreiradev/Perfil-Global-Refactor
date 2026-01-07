<?php
namespace App\Models;

use Config\Database;
use PDO;

class Persona {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function importarMasivo($datos) {
        try {
            $this->db->beginTransaction();

            // 1. Insertar o Actualizar en la tabla PERSONAS
            $sqlPersona = "INSERT INTO personas (numero_documento, nombres, apellidos, correo_institucional) 
                           VALUES (:doc, :nom, :ape, :cor)
                           ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), nombres=VALUES(nombres), apellidos=VALUES(apellidos)";
            
            // 2. Clasificar en HISTORIAL_ACADEMICO
            $sqlHistorial = "INSERT INTO historial_academico 
                             (persona_id, periodo_id, id_programa, id_tipo_asistente, nivel_formacion, semestre_cursado)
                             VALUES (:p_id, :per_id, :prog_id, :tipo_id, :nivel, :sem)
                             ON DUPLICATE KEY UPDATE id_programa=VALUES(id_programa), semestre_cursado=VALUES(semestre_cursado)";

            $stmtPers = $this->db->prepare($sqlPersona);
            $stmtHist = $this->db->prepare($sqlHistorial);

            foreach ($datos as $index => $fila) {
                if ($index === 0 || empty($fila[0])) continue;

                // Paso A: Persona
                $stmtPers->execute([
                    ':doc' => $fila[0],
                    ':nom' => $fila[1],
                    ':ape' => $fila[2],
                    ':cor' => $fila[3] ?? null
                ]);
                
                $personaId = $this->db->lastInsertId();

                // Paso B: Clasificación (Historial)
                $stmtHist->execute([
                    ':p_id'    => $personaId,
                    ':per_id'  => 1, // 2025-I
                    ':prog_id' => $fila[4] ?? 6, // Ing. Software por defecto
                    ':tipo_id' => 1, // Estudiante
                    ':nivel'   => 'Tecnólogo',
                    ':sem'     => $fila[5] ?? 1 
                ]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Método extra para mostrar resultados en el Dashboard
    public function obtenerUltimosRegistrados($limite = 10) {
        $sql = "SELECT p.numero_documento, p.nombres, p.apellidos, pr.nombre as programa, h.semestre_cursado 
                FROM personas p
                JOIN historial_academico h ON p.id = h.persona_id
                JOIN programas pr ON h.id_programa = pr.id_programa
                ORDER BY p.id DESC LIMIT :limite";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
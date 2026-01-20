<?php
namespace App\Models;
use Config\Database;

class HistorialAcademico {
    private $pdo;
    public function __construct() { $this->pdo = Database::getInstance(); }

    public function guardarOActualizar($data) {
        // Verificar si ya existe historial para esa persona en ese periodo
        $sql = "SELECT id FROM historial_academico WHERE persona_id = :pid AND periodo_id = :perid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':pid' => $data['persona_id'], ':perid' => $data['periodo_id']]);
        $existe = $stmt->fetch();

        if ($existe) {
            // UPDATE
            $sql = "UPDATE historial_academico SET 
                    id_programa = :prog, 
                    id_tipo_persona = :tipo, 
                    nivel_formacion = :nivel 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':prog' => $data['id_programa'],
                ':tipo' => $data['id_tipo_persona'],
                ':nivel'=> $data['nivel_formacion'],
                ':id'   => $existe['id']
            ]);
        } else {
            // INSERT
            $sql = "INSERT INTO historial_academico 
                    (persona_id, periodo_id, id_programa, id_tipo_persona, nivel_formacion) 
                    VALUES (:pid, :perid, :prog, :tipo, :nivel)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':pid'   => $data['persona_id'],
                ':perid' => $data['periodo_id'],
                ':prog'  => $data['id_programa'],
                ':tipo'  => $data['id_tipo_persona'],
                ':nivel' => $data['nivel_formacion']
            ]);
        }
    }
}
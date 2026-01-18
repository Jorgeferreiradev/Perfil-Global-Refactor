<?php
namespace App\Models;

use Config\Database;
use PDO;

class HistorialAcademico {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO historial_academico (persona_id, periodo_id, id_programa, semestre) 
                VALUES (:pid, :per, :prog, :sem)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':pid'  => $data['persona_id'],
            ':per'  => $data['periodo_id'],
            ':prog' => $data['id_programa'],
            ':sem'  => $data['semestre']
        ]);
    }

    public function existeEnPeriodo($personaId, $periodoId) {
        $sql = "SELECT id FROM historial_academico WHERE persona_id = :pid AND periodo_id = :per LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':pid' => $personaId, ':per' => $periodoId]);
        return $stmt->fetch();
    }
}
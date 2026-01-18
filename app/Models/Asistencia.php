<?php
namespace App\Models;

use Config\Database;
use PDO;

class Asistencia {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create($data) {
        // Verificar Modo Sandbox (HU-D02)
        $simulacion = isset($_SESSION['is_sandbox']) ? 1 : 0; 
        // Nota: Si es registro público, tal vez no detecte la sesión del admin. 
        // En un caso real, el "evento" ya tiene la marca de si es simulación o no.
        // Lo ideal: Heredar el flag 'es_simulacion' del evento padre.

        $sql = "INSERT INTO asistencias (id_evento, persona_id, ip_registro) VALUES (:evt, :per, :ip)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':evt' => $data['id_evento'],
            ':per' => $data['persona_id'],
            ':ip'  => $data['ip']
        ]);
    }

    public function yaRegistro($idEvento, $idPersona) {
        $sql = "SELECT id FROM asistencias WHERE id_evento = :evt AND persona_id = :per";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':evt' => $idEvento, ':per' => $idPersona]);
        return $stmt->fetch();
    }
}
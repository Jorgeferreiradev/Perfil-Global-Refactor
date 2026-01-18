<?php
namespace App\Models;

use Config\Database;
use PDO;

class Evento {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create($data) {
        // Verificar si estamos en modo Sandbox para marcar el flag
        $es_simulacion = isset($_SESSION['is_sandbox']) ? 1 : 0;

        $sql = "INSERT INTO eventos (
                    nombre_evento, token_qr, id_linea_accion, ano, semestre, 
                    fecha_inicio, fecha_final, hora_inicio, hora_final, 
                    sede, creado_por, es_simulacion
                ) VALUES (
                    :nombre, :token, :linea, :ano, :semestre, 
                    :f_inicio, :f_final, :h_inicio, :h_final, 
                    :sede, :creador, :simulacion
                )";

        $stmt = $this->pdo->prepare($sql);
        
        // Binding de parámetros
        $stmt->execute([
            ':nombre'   => $data['nombre'],
            ':token'    => $data['token'], // Generado en el Controller
            ':linea'    => $data['linea'],
            ':ano'      => date('Y'),
            ':semestre' => (date('m') <= 6) ? 'I' : 'II',
            ':f_inicio' => $data['fecha_inicio'],
            ':f_final'  => $data['fecha_final'],
            ':h_inicio' => $data['hora_inicio'],
            ':h_final'  => $data['hora_final'],
            ':sede'     => $data['sede'],
            ':creador'  => $_SESSION['user_id'],
            ':simulacion' => $es_simulacion
        ]);

        return $this->pdo->lastInsertId();
    }

    public function getAllByUser($userId) {
        // Si es Admin/Dev ve todo, si es Monitor solo los suyos
        if ($_SESSION['user_rol'] === 'admin' || $_SESSION['user_rol'] === 'dev') {
            $sql = "SELECT * FROM eventos WHERE deleted_at IS NULL ORDER BY fecha_inicio DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        } else {
            $sql = "SELECT * FROM eventos WHERE creado_por = :uid AND deleted_at IS NULL ORDER BY fecha_inicio DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':uid' => $userId]);
        }
        return $stmt->fetchAll();
    }
}
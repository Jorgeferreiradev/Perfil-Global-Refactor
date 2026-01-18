<?php
namespace App\Models;

use Config\Database;
use PDO;

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    /**
     * Busca usuario por correo para el Login
     */
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios_sistema WHERE correo = :correo AND deleted_at IS NULL LIMIT 1");
        $stmt->bindParam(':correo', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * HU-D04: Método para que el DEV limpie su simulación
     */
    public function cleanSimulationData() {
        // Borrar datos donde es_simulacion = 1 (Lógica sandbox)
        // Nota: Esto requiere que las tablas tengan esa columna.
        // $this->pdo->exec("DELETE FROM asistencias WHERE es_simulacion = 1");
    }
}
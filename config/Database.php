<?php
namespace Config;

use PDO;
use PDOException;
use Exception;

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // En producción, esto cargará desde el .env
        $host = 'localhost';
        $db   = 'perfilglobal_v2';
        $user = 'root';
        $pass = '';

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->conn;
    }
}
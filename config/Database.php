<?php
namespace  Config;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Cargar variables de entorno si usas PHP dotenv, o definir manual
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $db   = $_ENV['DB_NAME'] ?? 'pg';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? 'pg_admin*';

        try {
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            $this->conn = new PDO($dsn, $user, $pass);
            // Configuración de errores y modo de fetch por defecto
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error de conexión a BD: " . $e->getMessage());
        }
    }

    // Patrón Singleton: Solo una instancia
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
<?php

class ConexionDB
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "perfilglobal";
    private $conn;

    // Constructor para establecer la conexión
    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->database", $this->username, $this->password);
            // Habilita las excepciones PDO
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Conexión fallida: " . $e->getMessage();
        }
    }

    // Método para obtener la conexión
    public function obtenerConexion()
    {
        return $this->conn;
    }

    // Cierra la conexión cuando ya no se necesite
    public function cerrarConexion()
    {
        $this->conn = null;
    }
}

?>


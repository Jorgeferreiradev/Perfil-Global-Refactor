<?php
namespace App\Models;

use Config\Database;
use PDO;

class Persona {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getByDocumento($doc) {
        $stmt = $this->pdo->prepare("SELECT * FROM personas WHERE documento = :doc LIMIT 1");
        $stmt->execute([':doc' => $doc]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO personas (documento, nombres, apellidos, correo, tipo_comunidad) 
                VALUES (:doc, :nom, :ape, :cor, :com)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':doc' => $data['documento'],
            ':nom' => $data['nombres'],
            ':ape' => $data['apellidos'],
            ':cor' => $data['correo'],
            ':com' => $data['comunidad']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE personas SET nombres = :nom, apellidos = :ape, correo = :cor, tipo_comunidad = :com 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nombres'],
            ':ape' => $data['apellidos'],
            ':cor' => $data['correo'],
            ':com' => $data['comunidad'],
            ':id'  => $id
        ]);
    }
}
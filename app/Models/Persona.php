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
        $stmt = $this->pdo->prepare("SELECT * FROM personas WHERE numero_documento = :doc LIMIT 1");
        $stmt->execute([':doc' => $doc]);
        return $stmt->fetch();
    }

    public function create($data) {
        // ACTUALIZADO: Incluye id_tipo_persona
        $sql = "INSERT INTO personas (tipo_documento, numero_documento, nombres, apellidos, correo_institucional, id_tipo_persona) 
                VALUES (:tipo, :num, :nom, :ape, :cor, :id_tipo)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':tipo' => $data['tipo_documento'],
            ':num'  => $data['numero_documento'],
            ':nom'  => $data['nombres'],
            ':ape'  => $data['apellidos'],
            ':cor'  => $data['correo_institucional'],
            ':id_tipo' => $data['id_tipo_persona'] // Solución al error NOT NULL
        ]);
        
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        // ACTUALIZADO: Permite corregir el tipo de persona
        $sql = "UPDATE personas SET 
                nombres = :nom, 
                apellidos = :ape, 
                correo_institucional = :cor,
                id_tipo_persona = :id_tipo
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'   => $id,
            ':nom'  => $data['nombres'],
            ':ape'  => $data['apellidos'],
            ':cor'  => $data['correo_institucional'],
            ':id_tipo' => $data['id_tipo_persona']
        ]);
    }
}
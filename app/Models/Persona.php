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

    // Método para crear usuarios (Desde carga masiva o lógica interna)
    public function create($data) {
        $sql = "INSERT INTO personas (tipo_documento, numero_documento, nombres, apellidos, correo_institucional, id_tipo_persona) 
                VALUES (:tipo, :num, :nom, :ape, :cor, :id_tipo)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':tipo' => $data['tipo_documento'],
            ':num'  => $data['numero_documento'],
            ':nom'  => $data['nombres'],
            ':ape'  => $data['apellidos'],
            ':cor'  => $data['correo_institucional'],
            ':id_tipo' => $data['id_tipo_persona']
        ]);
        
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
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

    // Crear persona desde formulario manual
    public function createManual($data) {
        // 1. Insertamos Persona
        $sql = "INSERT INTO personas (
                    tipo_documento, numero_documento, nombres, apellidos, 
                    correo_institucional, estado_aprobacion
                ) VALUES (:td, :nd, :nom, :ape, :mail, :est)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':td'   => $data['tipo_doc'],
            ':nd'   => $data['documento'],
            ':nom'  => $data['nombres'],
            ':ape'  => $data['apellidos'],
            ':mail' => $data['correo'],
            ':est'  => $data['estado']
        ]);
        
        $idPersona = $this->pdo->lastInsertId();

        // 2. Insertamos Historial Académico Básico
        $sqlH = "INSERT INTO historial_academico (
                    persona_id, id_tipo_persona, id_programa, periodo_id
                ) VALUES (:pid, :tipo, 99, 1)"; 
        
        $stmtH = $this->pdo->prepare($sqlH);
        $stmtH->execute([
            ':pid'  => $idPersona,
            ':tipo' => $data['id_tipo']
        ]);

        return $idPersona;
    }

    public function getTipos() {
        return $this->pdo->query("SELECT * FROM tipos_personas")->fetchAll(\PDO::FETCH_ASSOC);
    }

    // TRAER PENDIENTES
    public function getPendientes() {
        $sql = "SELECT p.*, t.nombre_tipo 
                FROM personas p
                INNER JOIN historial_academico h ON p.id = h.persona_id
                INNER JOIN tipos_personas t ON h.id_tipo_persona = t.id_tipo
                WHERE p.estado_aprobacion = 'pendiente'
                ORDER BY p.id DESC";
        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    // [CORRECCIÓN 1] Agregado el método faltante para el contador del sidebar
    public function contarPendientes() {
        $sql = "SELECT COUNT(*) as total FROM personas WHERE estado_aprobacion = 'pendiente'";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $result['total'] : 0;
    }

    // APROBAR (Pasa a activo)
    public function aprobar($id) {
        $sql = "UPDATE personas SET estado_aprobacion = 'activo' WHERE id = :id";
        return $this->pdo->prepare($sql)->execute([':id' => $id]);
    }

    // [CORRECCIÓN 2] RECHAZAR (Ahora solo cambia estado, NO borra)
    public function rechazar($id) {
        // En lugar de DELETE, hacemos UPDATE a 'rechazado'.
        // Así la asistencia queda intacta (integridad referencial feliz) 
        // y la persona desaparece de la lista de pendientes.
        $sql = "UPDATE personas SET estado_aprobacion = 'rechazado' WHERE id = :id";
        return $this->pdo->prepare($sql)->execute([':id' => $id]);
    }
}
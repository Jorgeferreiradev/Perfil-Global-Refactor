<?php
namespace App\Models;

use \Config\Database;
use PDO;

class Persona {

    private $pdo;

    public function __construct() {
        // ✅ EL FIX: Usamos getInstance()->getConnection() para respetar tu Singleton
        // y obtener el objeto PDO puro para tus consultas.
        $this->pdo = Database::getInstance();
    }

    /* =====================================================
       CONSULTAS GENERALES (Tus métodos base)
    ===================================================== */

    public function getAll() {
        $sql = "SELECT 
                    p.*, 
                    tp.nombre_tipo,
                    (
                        SELECT pr.nombre_programa
                        FROM programas pr
                        INNER JOIN historial_academico ha 
                            ON ha.id_programa = pr.id_programa
                        WHERE ha.persona_id = p.id
                        LIMIT 1
                    ) AS programa_actual
                FROM personas p
                LEFT JOIN tipos_personas tp 
                    ON p.id_tipo_persona = tp.id_tipo
                WHERE p.deleted_at IS NULL
                ORDER BY p.apellidos ASC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($query) {
        $sql = "SELECT p.*, tp.nombre_tipo
                FROM personas p
                LEFT JOIN tipos_personas tp 
                    ON p.id_tipo_persona = tp.id_tipo
                WHERE p.deleted_at IS NULL
                AND (
                    p.numero_documento LIKE :q 
                    OR p.nombres LIKE :q 
                    OR p.apellidos LIKE :q
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':q' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una persona por ID (Incluyendo el teléfono que agregamos hoy)
    public function getById($id) {
        $sql = "SELECT * FROM personas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByDocumento($doc) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM personas WHERE numero_documento = :doc LIMIT 1"
        );
        $stmt->execute([':doc' => $doc]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       VALIDACIONES
    ===================================================== */

    public function existeDocumento($doc, $idExcluir = null) {
        $sql = "SELECT COUNT(*) 
                FROM personas 
                WHERE numero_documento = :doc 
                AND deleted_at IS NULL";

        $params = [':doc' => $doc];

        if ($idExcluir) {
            $sql .= " AND id != :id";
            $params[':id'] = $idExcluir;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    /* =====================================================
       CREACIÓN
    ===================================================== */

    public function create($data) {
        // Lógica: Si no hay tipo, por defecto 1 (Estudiante)
        $tipo = !empty($data['id_tipo_persona']) ? $data['id_tipo_persona'] : 1; 

        // Agregamos el campo 'telefono' aquí
        $sql = "INSERT INTO personas (tipo_documento, numero_documento, nombres, apellidos, correo_institucional, telefono, id_tipo_persona, estado_aprobacion) 
                VALUES (:td, :nd, :nom, :ape, :email, :tel, :tipo, 'activo')";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':td' => $data['tipo_documento'],
            ':nd' => $data['numero_documento'],
            ':nom' => $data['nombres'],
            ':ape' => $data['apellidos'],
            ':email' => $data['correo_institucional'],
            ':tel' => $data['telefono'] ?? null,
            ':tipo' => $tipo
        ]);
    }

    // ✅ TU MÉTODO PERSONALIZADO (RESTAURADO)
    public function createManual($data) {
        $sql = "INSERT INTO personas (
                    tipo_documento, numero_documento, 
                    nombres, apellidos, 
                    correo_institucional, estado_aprobacion
                ) VALUES (
                    :td, :nd, :nom, :ape, :mail, :estado
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':td'     => $data['tipo_doc'],
            ':nd'     => $data['documento'],
            ':nom'    => $data['nombres'],
            ':ape'    => $data['apellidos'],
            ':mail'   => $data['correo'],
            ':estado' => $data['estado']
        ]);

        $idPersona = $this->pdo->lastInsertId();

        // Inserción automática en historial (Lógica tuya)
        $sqlH = "INSERT INTO historial_academico (
                    persona_id, id_tipo_persona, id_programa, periodo_id
                ) VALUES (
                    :pid, :tipo, 99, 1
                )";

        $stmtH = $this->pdo->prepare($sqlH);
        $stmtH->execute([
            ':pid'  => $idPersona,
            ':tipo' => $data['id_tipo']
        ]);

        return $idPersona;
    }

    /* =====================================================
       ACTUALIZACIÓN
    ===================================================== */

    public function update($id, $data) {
        $sql = "UPDATE personas SET 
                tipo_documento = :td, 
                numero_documento = :nd, 
                nombres = :nom, 
                apellidos = :ape, 
                correo_institucional = :email, 
                telefono = :tel,  -- Agregado campo telefono
                id_tipo_persona = :tipo
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':td' => $data['tipo_documento'],
            ':nd' => $data['numero_documento'],
            ':nom' => $data['nombres'],
            ':ape' => $data['apellidos'],
            ':email' => $data['correo_institucional'],
            ':tel' => $data['telefono'] ?? null,
            ':tipo' => $data['id_tipo_persona'],
            ':id' => $id
        ]);
    }

    /* =====================================================
       ELIMINACIÓN LÓGICA
    ===================================================== */

    public function delete($id) {
        return $this->pdo
            ->prepare("UPDATE personas SET deleted_at = NOW() WHERE id = :id")
            ->execute([':id' => $id]);
    }

    /* =====================================================
       TUS MÉTODOS DE TIPOS Y APROBACIONES (RESTAURADOS)
    ===================================================== */

    public function getTipos() {
        return $this->pdo
            ->query("SELECT * FROM tipos_personas")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendientes() {
        $sql = "SELECT p.*, t.nombre_tipo
                FROM personas p
                INNER JOIN historial_academico h 
                    ON p.id = h.persona_id
                INNER JOIN tipos_personas t 
                    ON h.id_tipo_persona = t.id_tipo
                WHERE p.estado_aprobacion = 'pendiente'
                ORDER BY p.id DESC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPendientes() {
        return (int) $this->pdo
            ->query("SELECT COUNT(*) FROM personas WHERE estado_aprobacion = 'pendiente'")
            ->fetchColumn();
    }

    public function aprobar($id) {
        return $this->pdo
            ->prepare("UPDATE personas SET estado_aprobacion = 'activo' WHERE id = :id")
            ->execute([':id' => $id]);
    }

    public function rechazar($id) {
        return $this->pdo
            ->prepare("UPDATE personas SET estado_aprobacion = 'rechazado' WHERE id = :id")
            ->execute([':id' => $id]);
    }
}
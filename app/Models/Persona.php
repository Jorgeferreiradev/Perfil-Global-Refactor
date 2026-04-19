<?php
namespace App\Models;

use \Config\Database;
use PDO;

class Persona {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    /* =====================================================
        CONSULTAS GENERALES (Listado y Búsqueda unificados)
    ===================================================== */

    public function getAll() {
        $periodoId = $_SESSION['periodo_vista_id'];

        $sql = "SELECT 
                    p.*, 
                    tp.nombre_tipo,
                    ha.nivel_formacion AS nivel_actual,
                    pr.nombre_programa AS programa_actual
                FROM personas p
                INNER JOIN historial_academico ha ON p.id = ha.persona_id
                INNER JOIN tipos_personas tp ON ha.id_tipo_persona = tp.id_tipo
                INNER JOIN programas pr ON ha.id_programa = pr.id_programa
                WHERE ha.periodo_id = :periodo
                AND p.deleted_at IS NULL
                ORDER BY p.apellidos ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':periodo' => $periodoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($query) {
        $periodoId = $_SESSION['periodo_vista_id'];

        $sql = "SELECT 
                    p.*, 
                    tp.nombre_tipo,
                    ha.nivel_formacion AS nivel_actual,
                    pr.nombre_programa AS programa_actual
                FROM personas p
                INNER JOIN historial_academico ha ON p.id = ha.persona_id
                INNER JOIN tipos_personas tp ON ha.id_tipo_persona = tp.id_tipo
                INNER JOIN programas pr ON ha.id_programa = pr.id_programa
                WHERE ha.periodo_id = :periodo
                AND p.deleted_at IS NULL
                AND (
                    p.numero_documento LIKE :q 
                    OR p.nombres LIKE :q 
                    OR p.apellidos LIKE :q
                )
                ORDER BY p.apellidos ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':periodo' => $periodoId,
            ':q' => "%$query%"
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT p.*, ha.id_programa 
                FROM personas p
                LEFT JOIN historial_academico ha ON p.id = ha.persona_id 
                AND ha.periodo_id = :periodo
                WHERE p.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id, 
            ':periodo' => $_SESSION['periodo_vista_id']
        ]);
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
        $periodo = $_SESSION['periodo_vista_id'];
        $sql = "SELECT COUNT(*) FROM personas p
                INNER JOIN historial_academico ha ON p.id = ha.persona_id
                WHERE p.numero_documento = :doc 
                AND ha.periodo_id = :periodo 
                AND p.deleted_at IS NULL";

        $params = [':doc' => $doc, ':periodo' => $periodo];

        if ($idExcluir) {
            $sql .= " AND p.id != :id";
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
        $tipo = !empty($data['id_tipo_persona']) ? $data['id_tipo_persona'] : 1; 

        $sql = "INSERT INTO personas (tipo_documento, numero_documento, nombres, apellidos, correo_institucional, telefono, id_tipo_persona, estado_aprobacion) 
                VALUES (:td, :nd, :nom, :ape, :email, :tel, :tipo, 'activo')";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':td' => $data['tipo_documento'],
            ':nd' => $data['numero_documento'],
            ':nom' => $data['nombres'],
            ':ape' => $data['apellidos'],
            ':email' => $data['correo_institucional'],
            ':tel' => $data['telefono'] ?? null,
            ':tipo' => $tipo
        ]);

        $idPersona = $this->pdo->lastInsertId();

        // 🔥 FIX: Ahora usamos el id_programa que viene del formulario, o 99 si no hay nada.
        $programa = !empty($data['id_programa']) ? $data['id_programa'] : 99;
        $periodoActual = $_SESSION['periodo_vista_id'];

        $sqlH = "INSERT INTO historial_academico (persona_id, id_tipo_persona, id_programa, periodo_id) 
                 VALUES (:pid, :tipo, :prog, :periodo)";
        $stmtH = $this->pdo->prepare($sqlH);
        $stmtH->execute([
            ':pid' => $idPersona,
            ':tipo' => $tipo,
            ':prog' => $programa,
            ':periodo' => $periodoActual
        ]);

        return $idPersona;
    }

    public function createManual($data) {
        $sql = "INSERT INTO personas (
                    tipo_documento, numero_documento, 
                    nombres, apellidos, 
                    correo_institucional, telefono, estado_aprobacion
                ) VALUES (
                    :td, :nd, :nom, :ape, :mail, :tel, :estado
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':td'     => $data['tipo_doc'],
            ':nd'     => $data['documento'],
            ':nom'    => $data['nombres'],
            ':ape'    => $data['apellidos'],
            ':mail'   => $data['correo'],
            ':tel'    => $data['celular'],
            ':estado' => $data['estado']
        ]);

        $idPersona = $this->pdo->lastInsertId();

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
                telefono = :tel, 
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
        MÉTODOS DE APOYO
    ===================================================== */

    public function getTipos() {
        return $this->pdo
            ->query("SELECT * FROM tipos_personas")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProgramas() {
        return $this->pdo
            ->query("SELECT id_programa, nombre_programa FROM programas WHERE id_programa != 99 ORDER BY nombre_programa ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendientes() {
        $sql = "SELECT p.*, t.nombre_tipo
                FROM personas p
                INNER JOIN historial_academico h ON p.id = h.persona_id
                INNER JOIN tipos_personas t ON h.id_tipo_persona = t.id_tipo
                WHERE p.estado_aprobacion = 'pendiente'
                ORDER BY p.id DESC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPendientes() {
        $periodo = $_SESSION['periodo_vista_id'];
        $sql = "SELECT COUNT(DISTINCT p.id) 
                FROM personas p
                INNER JOIN historial_academico ha ON p.id = ha.persona_id
                WHERE p.estado_aprobacion = 'pendiente' 
                AND ha.periodo_id = :periodo";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':periodo' => $periodo]);
        return (int) $stmt->fetchColumn();
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
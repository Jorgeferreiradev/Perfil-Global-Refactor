<?php
namespace App\Models;

use Config\Database;
use PDO;

class Evento {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // 1. FILTROS AVANZADOS (CU-C01 y Requerimiento 4)
    public function all($filtros = []) {
        // NOTA SENIOR: Usamos 'LEFT JOIN' en programas por si algún evento antiguo no tiene programa asignado
        $sql = "SELECT e.*, l.nombre_linea, p.nombre_programa as programa_nombre 
                FROM eventos e 
                INNER JOIN lineas_accion l ON e.id_linea_accion = l.id
                LEFT JOIN programas p ON e.programa_responsable = p.id_programa
                WHERE 1=1"; 

        $params = [];

        // --- APLICACIÓN DE FILTROS ---
        
        // A. Por Línea de Acción
        if (!empty($filtros['linea'])) {
            $sql .= " AND e.id_linea_accion = :linea";
            $params[':linea'] = $filtros['linea'];
        }

        // B. Por Programa Académico (Vital para Matriz Excel)
        if (!empty($filtros['programa'])) {
            $sql .= " AND e.programa_responsable = :prog";
            $params[':prog'] = $filtros['programa'];
        }

        // C. Por Fecha Específica
        if (!empty($filtros['fecha'])) {
            $sql .= " AND DATE(e.fecha_inicio) = :fecha";
            $params[':fecha'] = $filtros['fecha'];
        }

        // D. Búsqueda por texto (Nombre del evento)
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND e.nombre_evento LIKE :busq";
            $params[':busq'] = "%" . $filtros['busqueda'] . "%";
        }

        $sql .= " ORDER BY e.fecha_inicio DESC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Manejo de error silencioso o debug
            die("Error crítico en Evento::all - " . $e->getMessage());
        }
    }

    // 2. CREACIÓN (Genera Token QR)
    public function create($data) {
        $token = bin2hex(random_bytes(8)); // Genera ej: a1b2c3d4

        $sql = "INSERT INTO eventos (
                    nombre_evento, id_linea_accion, programa_responsable, sede, 
                    fecha_inicio, hora_inicio, fecha_final, hora_final, 
                    id_periodo, creado_por, token_qr
                ) VALUES (
                    :nom, :linea, :prog, :sede, 
                    :f_ini, :h_ini, :f_fin, :h_fin, 
                    :periodo, :creador, :token
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'     => $data['nombre_evento'],
            ':linea'   => $data['id_linea_accion'],
            ':prog'    => $data['programa_responsable'],
            ':sede'    => $data['sede'],
            ':f_ini'   => $data['fecha_inicio'],
            ':h_ini'   => $data['hora_inicio'],
            ':f_fin'   => $data['fecha_final'],
            ':h_fin'   => $data['hora_final'],
            ':periodo' => $data['id_periodo'],
            ':creador' => $data['creado_por'],
            ':token'   => $token
        ]);
        return $this->pdo->lastInsertId();
    }

    // 3. OBTENER POR ID (Para Edición y Asistentes)
    public function getById($id) {
        $sql = "SELECT e.*, l.nombre_linea, p.nombre_programa 
                FROM eventos e
                LEFT JOIN lineas_accion l ON e.id_linea_accion = l.id
                LEFT JOIN programas p ON e.programa_responsable = p.id_programa
                WHERE e.id_evento = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. OBTENER POR TOKEN (Para el QR)
    public function getByToken($token) {
        $stmt = $this->pdo->prepare("SELECT * FROM eventos WHERE token_qr = ? LIMIT 1");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. UPDATE (CRUD - Requerimiento 5)
    // IMPORTANTE: NO tocamos 'token_qr' para no romper los QRs ya impresos
    public function update($id, $data) {
        $sql = "UPDATE eventos SET 
                    nombre_evento = :nom,
                    id_linea_accion = :linea,
                    programa_responsable = :prog,
                    sede = :sede,
                    fecha_inicio = :f_ini,
                    hora_inicio = :h_ini,
                    fecha_final = :f_fin,
                    hora_final = :h_fin
                WHERE id_evento = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom'     => $data['nombre_evento'],
            ':linea'   => $data['id_linea_accion'],
            ':prog'    => $data['programa_responsable'],
            ':sede'    => $data['sede'],
            ':f_ini'   => $data['fecha_inicio'],
            ':h_ini'   => $data['hora_inicio'],
            ':f_fin'   => $data['fecha_final'],
            ':h_fin'   => $data['hora_final'],
            ':id'      => $id
        ]);
    }

    // Helpers
    public function getLineas() { 
        return $this->pdo->query("SELECT * FROM lineas_accion ORDER BY nombre_linea")->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function getProgramas() { 
        // Filtramos ID 99 si es necesario, o lo dejamos si "Externo" puede crear eventos
        return $this->pdo->query("SELECT * FROM programas WHERE id_programa != 99 ORDER BY nombre_programa")->fetchAll(PDO::FETCH_ASSOC); 
    }
}
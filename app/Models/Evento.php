<?php
namespace App\Models;

use Config\Database;
use PDO;

class Evento {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    /* =====================================================
       1. LISTAR EVENTOS (CON FILTROS + SOLO ACTIVOS)
    ===================================================== */
    public function all($filtros = []) {

        $sql = "SELECT 
                    e.*, 
                    l.nombre_linea, 
                    p.nombre_programa AS programa_nombre
                FROM eventos e
                INNER JOIN lineas_accion l ON e.id_linea_accion = l.id
                LEFT JOIN programas p ON e.programa_responsable = p.id_programa
                WHERE e.estado = 'activo'";

        $params = [];

        // Filtro por línea
        if (!empty($filtros['linea'])) {
            $sql .= " AND e.id_linea_accion = :linea";
            $params[':linea'] = $filtros['linea'];
        }

        // Filtro por programa
        if (!empty($filtros['programa'])) {
            $sql .= " AND e.programa_responsable = :prog";
            $params[':prog'] = $filtros['programa'];
        }

        // Filtro por fecha
        if (!empty($filtros['fecha'])) {
            $sql .= " AND DATE(e.fecha_inicio) = :fecha";
            $params[':fecha'] = $filtros['fecha'];
        }

        // Búsqueda por nombre
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND e.nombre_evento LIKE :busq";
            $params[':busq'] = '%' . $filtros['busqueda'] . '%';
        }

        $sql .= " ORDER BY e.fecha_inicio DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       2. CREAR EVENTO
    ===================================================== */
    public function create($data) {

        $token = bin2hex(random_bytes(8));

        $sql = "INSERT INTO eventos (
                    nombre_evento,
                    id_linea_accion,
                    programa_responsable,
                    sede,
                    fecha_inicio,
                    hora_inicio,
                    fecha_final,
                    hora_final,
                    id_periodo,
                    creado_por,
                    token_qr,
                    estado
                ) VALUES (
                    :nom,
                    :linea,
                    :prog,
                    :sede,
                    :f_ini,
                    :h_ini,
                    :f_fin,
                    :h_fin,
                    :periodo,
                    :creador,
                    :token,
                    'activo'
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

    /* =====================================================
       3. OBTENER EVENTO POR ID
    ===================================================== */
    public function getById($id) {

        $sql = "SELECT 
                    e.*, 
                    l.nombre_linea, 
                    p.nombre_programa
                FROM eventos e
                LEFT JOIN lineas_accion l ON e.id_linea_accion = l.id
                LEFT JOIN programas p ON e.programa_responsable = p.id_programa
                WHERE e.id_evento = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       4. OBTENER EVENTO POR TOKEN (QR)
    ===================================================== */
    public function getByToken($token) {

        $stmt = $this->pdo->prepare(
            "SELECT * FROM eventos 
             WHERE token_qr = ? AND estado = 'activo' 
             LIMIT 1"
        );

        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       5. ACTUALIZAR EVENTO
       (NO se toca token_qr)
    ===================================================== */
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
            ':nom'   => $data['nombre_evento'],
            ':linea' => $data['id_linea_accion'],
            ':prog'  => $data['programa_responsable'],
            ':sede'  => $data['sede'],
            ':f_ini' => $data['fecha_inicio'],
            ':h_ini' => $data['hora_inicio'],
            ':f_fin' => $data['fecha_final'],
            ':h_fin' => $data['hora_final'],
            ':id'    => $id
        ]);
    }

    /* =====================================================
       6. ELIMINAR EVENTO (SOFT DELETE)
    ===================================================== */
    public function delete($id) {

        $sql = "UPDATE eventos 
                SET estado = 'inactivo' 
                WHERE id_evento = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /* =====================================================
       HELPERS
    ===================================================== */
    public function getLineas() {
        return $this->pdo
            ->query("SELECT * FROM lineas_accion ORDER BY nombre_linea")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProgramas() {
        return $this->pdo
            ->query("SELECT * FROM programas WHERE id_programa != 99 ORDER BY nombre_programa")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}

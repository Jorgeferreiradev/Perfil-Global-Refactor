<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION["usuario"])) {
    http_response_code(401);
    echo json_encode(["error" => "No autorizado."]);
    exit();
}

require_once '../../conexion.php';

try {
    $db = new ConexionDB();
    $pdo = $db->obtenerConexion();

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID inválido o no enviado."]);
        exit();
    }

    $id = intval($_GET['id']);

    $stmt = $pdo->prepare("
        SELECT 
            a.id,
            a.cedula, 
            a.nombre_completo, 
            a.id_programa,
            a.id_tipo, 
            a.ano, 
            a.semestre,
            p.nombre AS nombre_programa, 
            t.tipo AS nombre_tipo
        FROM asistentes a
        JOIN programas p ON a.id_programa = p.id_programa
        JOIN tipos_asistentes t ON a.id_tipo = t.id_tipo
        WHERE a.id = ?
    ");

    $stmt->execute([$id]);
    $asistente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($asistente) {
        echo json_encode($asistente);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Asistente no encontrado."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}

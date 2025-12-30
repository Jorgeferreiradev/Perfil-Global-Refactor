<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'monitor') {
    http_response_code(403);
    echo "Acceso denegado.";
    exit();
}

require_once('../../conexion.php');
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');
    $id_evento = intval($_POST['id_evento'] ?? 0);

    if (empty($cedula) || $id_evento <= 0) {
        http_response_code(400);
        echo "Cédula y evento son obligatorios.";
        exit();
    }

    // Buscar asistente
    $stmt = $pdo->prepare("SELECT id FROM asistentes WHERE cedula = :cedula");
    $stmt->execute(['cedula' => $cedula]);
    $asistente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$asistente) {
        echo "Asistente no encontrado.";
        exit();
    }

    // Eliminar asistencia
    $stmtDel = $pdo->prepare("DELETE FROM asistencia WHERE id_asistente = :id_asistente AND id_evento = :id_evento");
    $stmtDel->execute(['id_asistente' => $asistente['id'], 'id_evento' => $id_evento]);

    if ($stmtDel->rowCount() > 0) {
        echo "Asistencia eliminada correctamente.";
    } else {
        echo "No se encontró asistencia registrada para este evento.";
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}

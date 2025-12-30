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
    echo "USUARIO NO EXISTE, PUEDE REGISTRARLO COMO INVITADO";
    exit();
}
$id_asistente = $asistente['id'];


    // Verificar duplicidad
    $stmtCheck = $pdo->prepare("SELECT id FROM asistencia WHERE id_asistente = :id_asistente AND id_evento = :id_evento");
    $stmtCheck->execute(['id_asistente' => $id_asistente, 'id_evento' => $id_evento]);
    if ($stmtCheck->fetch()) {
        echo "Este asistente ya está registrado en este evento.";
        exit();
    }

    // Registrar asistencia
    $stmtInsertAsis = $pdo->prepare("INSERT INTO asistencia (id_asistente, id_evento, fecha_registro) VALUES (:id_asistente, :id_evento, NOW())");
    if ($stmtInsertAsis->execute(['id_asistente' => $id_asistente, 'id_evento' => $id_evento])) {
        echo "Asistencia registrada correctamente.";
    } else {
        http_response_code(500);
        echo "Error al registrar la asistencia.";
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}

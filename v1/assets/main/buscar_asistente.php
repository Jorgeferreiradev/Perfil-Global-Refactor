<?php
header('Content-Type: application/json');

require_once("../../conexion.php");

$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

$cedula = $_GET['cedula'] ?? null;
$id_evento = $_GET['id_evento'] ?? null;

if (!$cedula) {
    echo json_encode([
        "existe" => false,
        "mensaje" => "❌ Debes ingresar una cédula válida."
    ]);
    exit;
}

// Buscar asistente por cédula
$stmt = $pdo->prepare("SELECT * FROM asistentes WHERE cedula = ?");
$stmt->execute([$cedula]);
$asistente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($asistente) {
    echo json_encode([
        "existe" => true,
        "datos" => $asistente
    ]);
} else {
    echo json_encode([
        "existe" => false,
        "mensaje" => "⚠️ Asistente no registrado en la base de datos."
    ]);
}

<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'monitor') {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Acceso denegado."]);
    exit();
}

require_once('../../conexion.php');
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');

    if (empty($cedula)) {
        echo json_encode(["success" => false, "message" => "La cédula es obligatoria."]);
        exit();
    }

    $stmt = $pdo->prepare("SELECT id FROM asistentes WHERE cedula = :cedula");
    $stmt->execute(['cedula' => $cedula]);
    $existe = $stmt->fetch();

    if ($existe) {
        // Ya existe el asistente
        echo json_encode(["success" => true, "message" => "El usuario ya existe."]);
    } else {
        // No existe, se permite continuar con el registro
        echo json_encode(["success" => false, "message" => "Cédula disponible."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}

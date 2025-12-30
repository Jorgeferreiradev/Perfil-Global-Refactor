<?php
session_start();
require_once("../../conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../../login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$db = new ConexionDB();
$conn = $db->obtenerConexion();

$sql = "SELECT a.*, p.nombre AS programa, t.tipo AS tipo_asistente
        FROM asistentes a
        JOIN programas p ON a.id_programa = p.id
        JOIN tipos_asistentes t ON a.id_tipo_asistente = t.id
        WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$asistente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$asistente) {
    echo "<div class='alert alert-danger'>Asistente no encontrado.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Asistente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h4>Detalles del Asistente</h4>
    <table class="table">
        <tr><th>Cédula</th><td><?= htmlspecialchars($asistente['cedula']) ?></td></tr>
        <tr><th>Nombre</th><td><?= htmlspecialchars($asistente['nombre_completo']) ?></td></tr>
        <tr><th>Programa</th><td><?= htmlspecialchars($asistente['programa']) ?></td></tr>
        <tr><th>Tipo de Asistente</th><td><?= htmlspecialchars($asistente['tipo_asistente']) ?></td></tr>
        <tr><th>Año</th><td><?= htmlspecialchars($asistente['ano']) ?></td></tr>
        <tr><th>Semestre</th><td><?= $asistente['semestre'] == 1 ? "Enero - Junio" : "Julio - Diciembre" ?></td></tr>
    </table>
    <a href="index.php" class="btn btn-secondary">Volver</a>
</div>
</body>
</html>

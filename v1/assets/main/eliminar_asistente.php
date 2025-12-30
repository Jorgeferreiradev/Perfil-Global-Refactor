<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../../login.php");
    exit();
}

require_once("../../conexion.php");
$db   = new ConexionDB();
$conn = $db->obtenerConexion();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM asistentes WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: admin_03_gestion_asistentes.php");
exit();

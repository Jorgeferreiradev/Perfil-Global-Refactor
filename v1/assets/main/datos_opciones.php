<?php
require_once("../../conexion.php");
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

header('Content-Type: application/json');

$programas = $pdo->query("SELECT id_programa, nombre FROM programas ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$tipos = $pdo->query("SELECT id_tipo, tipo FROM tipos_asistentes ORDER BY tipo ASC")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "programas" => $programas,
    "tipos" => $tipos
]);

<?php
require_once("../../conexion.php");
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST["cedula"];
    $nombre = trim($_POST["nombre_completo"]);
    $programa = $_POST["id_programa"];
    $tipo = $_POST["id_tipo"];
    $ano = $_POST["ano"];
    $semestre = $_POST["semestre"];

    // Verificar duplicado
    $stmt = $pdo->prepare("SELECT * FROM asistentes WHERE cedula = ?");
    $stmt->execute([$cedula]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "Ya existe un asistente con esa cédula."]);
        exit;
    }

    // Insertar nuevo asistente
    $sql = "INSERT INTO asistentes (cedula, nombre_completo, id_programa, id_tipo, ano, semestre)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $exito = $stmt->execute([$cedula, $nombre, $programa, $tipo, $ano, $semestre]);

    if ($exito) {
        // Obtener nombre del programa para mostrarlo
        $stmtProg = $pdo->prepare("SELECT nombre FROM programas WHERE id_programa = ?");
        $stmtProg->execute([$programa]);
        $nombre_programa = $stmtProg->fetchColumn();

        echo json_encode([
            "success" => true,
            "nombre_completo" => $nombre,
            "nombre_programa" => $nombre_programa
            
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar el nuevo asistente."]);
    }
}

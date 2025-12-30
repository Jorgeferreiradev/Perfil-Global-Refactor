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

$cedula = trim($_GET['cedula'] ?? '');

if (empty($cedula)) {
    echo "Cédula no proporcionada.";
    exit();
}

$sql = "
    SELECT 
        a.cedula,
        a.nombre_completo,
        a.ano,
        a.semestre,
        p.nombre AS nombre_programa,
        p.modalidad,
        t.tipo AS tipo_asistente
    FROM asistentes a
    LEFT JOIN programas p ON a.id_programa = p.id_programa
    LEFT JOIN tipos_asistentes t ON a.id_tipo = t.id_tipo
    WHERE a.cedula = :cedula
";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['cedula' => $cedula]);
    $asistente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($asistente) {
        echo "<p><strong>Cédula:</strong> " . htmlspecialchars($asistente['cedula']) . "</p>";
        echo "<p><strong>Nombre completo:</strong> " . htmlspecialchars($asistente['nombre_completo']) . "</p>";
        echo "<p><strong>Programa académico:</strong> " . htmlspecialchars($asistente['nombre_programa']) . " (" . htmlspecialchars($asistente['modalidad']) . ")</p>";
        echo "<p><strong>Tipo de asistente:</strong> " . htmlspecialchars($asistente['tipo_asistente']) . "</p>";
        echo "<p><strong>Año actual:</strong> " . htmlspecialchars($asistente['ano']) . "</p>";
        echo "<p><strong>Semestre:</strong> " . htmlspecialchars($asistente['semestre']) . "</p>";
    } else {
        echo "Asistente no encontrado.";
    }
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}

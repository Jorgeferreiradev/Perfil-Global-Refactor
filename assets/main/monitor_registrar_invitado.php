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

    // Verificar si el asistente ya existe
    $stmt = $pdo->prepare("SELECT id FROM asistentes WHERE cedula = :cedula");
    $stmt->execute(['cedula' => $cedula]);
    $asistente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$asistente) {
        // Insertar nuevo asistente tipo invitado (tipo_asistente_id=5)
        $stmtInsert = $pdo->prepare("INSERT INTO asistentes (cedula, nombre_completo, id_programa, id_tipo, ano, semestre) VALUES (:cedula, :nombre_completo, :id_programa, 5, :ano, :semestre)");
        $stmtInsert->execute([
            'cedula' => $cedula,
            'nombre_completo' => 'Invitado',
            'id_programa' => $id_programa,
            'ano' => date('Y'),
            'semestre' => date('n') <= 6 ? 1 : 2
        ]);
        $id_asistente = $pdo->lastInsertId();
    } else {
        $id_asistente = $asistente['id_asistente'];
    }

    // Verificar si ya está registrado en el evento
    $stmtCheck = $pdo->prepare("SELECT * FROM asistencia WHERE id_asistente = :id_asistente AND id_evento = :id_evento");
    $stmtCheck->execute(['id_asistente' => $id_asistente, 'id_evento' => $id_evento]);
    if ($stmtCheck->fetch()) {
        echo "El invitado ya está registrado en este evento.";
        exit();
    }

    // Registrar asistencia como invitado
    $stmtInsertAsis = $pdo->prepare("INSERT INTO asistencia (id_asistente, id_evento, fecha_registro) VALUES (:id_asistente, :id_evento, NOW())");
    if ($stmtInsertAsis->execute(['id_asistente' => $id_asistente, 'id_evento' => $id_evento])) {
        echo "Invitado registrado correctamente.";
    } else {
        http_response_code(500);
        echo "Error al registrar al invitado.";
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}

<?php
require_once('../../conexion.php');
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');
    $id_evento = $_POST['id_evento'] ?? '';

    if (!$cedula || !$id_evento) {
        echo "Datos incompletos.";
        exit;
    }

    // Buscar si ya existe el asistente
    $stmt = $pdo->prepare("SELECT id FROM asistentes WHERE cedula = :cedula");
    $stmt->execute([':cedula' => $cedula]);
    $asistente = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si no existe, lo registramos como INVITADO
    if (!$asistente) {
        // Ajusta esto si tu valor real de tipo "Invitado" es diferente
        $id_tipo_asistente_invitado = 5;
        $id_programa_generico = 0;

        $nombre_generico = "Invitado $cedula";
        $ano_actual = date('Y');
        $semestre_actual = (date('n') <= 6) ? 1 : 2;

        $stmt_insert = $pdo->prepare("INSERT INTO asistentes 
            (cedula, nombre_completo, id_programa, id_tipo, ano, semestre)
            VALUES (:cedula, :nombre, :id_programa, :id_tipo, :ano, :semestre)");

        $stmt_insert->execute([
            ':cedula' => $cedula,
            ':nombre' => $nombre_generico,
            ':id_programa' => $id_programa_generico,
            ':id_tipo' => $id_tipo_asistente_invitado,
            ':ano' => $ano_actual,
            ':semestre' => $semestre_actual
        ]);

        // Volvemos a buscar el ID recién insertado
        $stmt = $pdo->prepare("SELECT id FROM asistentes WHERE cedula = :cedula");
        $stmt->execute([':cedula' => $cedula]);
        $asistente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$asistente) {
            echo "❌ No se pudo registrar al invitado.";
            exit;
        }
    }

    $id_asistente = $asistente['id'];

    // Verificar si ya está registrado en ese evento
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM asistencia WHERE id_asistente = :id_asistente AND id_evento = :id_evento");
    $stmt->execute([
        ':id_asistente' => $id_asistente,
        ':id_evento' => $id_evento
    ]);

    if ($stmt->fetchColumn() > 0) {
        echo "Este asistente ya fue registrado en este evento.";
        exit;
    }

    // Registrar asistencia
    $stmt = $pdo->prepare("INSERT INTO asistencia (id_asistente, id_evento) VALUES (:id_asistente, :id_evento)");
    if ($stmt->execute([
        ':id_asistente' => $id_asistente,
        ':id_evento' => $id_evento
    ])) {
        echo "✅ Asistente registrado correctamente.";
    } else {
        echo "❌ Error al registrar la asistencia.";
    }
}
?>

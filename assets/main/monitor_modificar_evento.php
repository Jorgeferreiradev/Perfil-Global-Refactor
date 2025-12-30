<?php
session_start();
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'monitor' && $_SESSION['rol'] !== 'administrador')) {
    header("Location: ../../login.php");
    exit();
}

require_once('../../conexion.php');
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

// Obtener el ID del evento
$id_evento = filter_input(INPUT_GET, 'id_evento', FILTER_VALIDATE_INT);
if (!$id_evento) {
    die("❌ ID de evento inválido.");
}

// Obtener datos actuales del evento
$consulta_evento = $pdo->prepare("
    SELECT nombre_evento, fecha_inicio, fecha_final, id_linea_accion, programa_responsable
    FROM eventos
    WHERE id_evento = ?
");
$consulta_evento->execute([$id_evento]);
$evento = $consulta_evento->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    die("⚠️ No se encontró el evento con ID: $id_evento.");
}

// Obtener todas las líneas de acción
$consulta_lineas = $pdo->query("SELECT id, nombre FROM lineas_accion ORDER BY nombre ASC");
$lineas_accion = $consulta_lineas->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los programas
$consulta_programas = $pdo->query("SELECT id_programa, nombre FROM programas ORDER BY nombre ASC");
$programas = $consulta_programas->fetchAll(PDO::FETCH_ASSOC);


// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre_evento = trim($_POST['nombre_evento']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_final = $_POST['fecha_final'];
    $id_linea_accion = intval($_POST['id_linea_accion']);
    $programa_responsable = intval($_POST['programa_responsable']);


    // Validación básica
    if (empty($nombre_evento) || empty($fecha_inicio) || empty($fecha_final)) {
        $error = "⚠️ Todos los campos son obligatorios.";
    } else {
        // Actualizar evento
        $actualizar_evento = $pdo->prepare("
            UPDATE eventos 
            SET nombre_evento = ?, fecha_inicio = ?, fecha_final = ?, id_linea_accion = ?, programa_responsable = ?
            WHERE id_evento = ?
        ");
        $resultado = $actualizar_evento->execute([$nombre_evento, $fecha_inicio, $fecha_final, $id_linea_accion, $programa_responsable, $id_evento]);

        if ($resultado) {
            header("Location: monitor_03_consultar_eventos.php?id_evento=$id_evento&msg=✔️ Evento actualizado correctamente.");
            exit();
        } else {
            $error = "❌ Error al actualizar el evento.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include_once("../../componentes/header.php"); HeaderPersonalizado::mostrar(); ?>

<div class="container mt-5">
    <h2 class="mb-4">✏️ Modificar Evento</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label class="form-label">Nombre del Evento</label>
            <input type="text" name="nombre_evento" class="form-control" value="<?= htmlspecialchars($evento['nombre_evento']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($evento['fecha_inicio']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Final</label>
            <input type="date" name="fecha_final" class="form-control" value="<?= htmlspecialchars($evento['fecha_final']) ?>" required>
        </div>

        <div class="mb-3">
    <label class="form-label">Línea de Acción</label>
    <select name="id_linea_accion" class="form-control" required>
        <?php foreach ($lineas_accion as $linea): ?>
            <option value="<?= $linea['id'] ?>" <?= ($linea['id'] == $evento['id_linea_accion']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($linea['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Programa Responsable</label>
    <select name="programa_responsable" class="form-control" required>
        <?php foreach ($programas as $programa): ?>
            <option value="<?= $programa['id_programa'] ?>" <?= ($programa['id_programa'] == $evento['programa_responsable']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($programa['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

        <div class="text-center d-flex justify-content-center gap-3">
    <button type="submit" class="btn btn-success">✔️ Guardar Cambios</button>
    <a href="monitor_ver_evento.php?id_evento=<?= $id_evento ?>" class="btn btn-secondary">↩️ Volver a Consultas</a>
</div>
    </form>
</div>

<?php include_once("../../componentes/footer.php"); Footer::mostrar(); ?>

</body>
</html>
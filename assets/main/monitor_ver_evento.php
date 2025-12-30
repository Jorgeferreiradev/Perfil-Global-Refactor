<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'monitor') {
    header("Location: ../../login.php");
    exit();
}

require_once('../../conexion.php');
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

// Validar el ID del evento recibido por GET
$id_evento = isset($_GET['id_evento']) && is_numeric($_GET['id_evento']) ? intval($_GET['id_evento']) : 0;
if ($id_evento <= 0) {
    die("❌ Evento no válido.");
}

// Obtener filtro por cédula (opcional)
$f_cedula = $_GET['cedula'] ?? '';

// Obtener información del evento
$consulta_evento = $pdo->prepare("
    SELECT e.nombre_evento, e.fecha_inicio, e.fecha_final,
           l.nombre AS linea, p.nombre AS programa
    FROM eventos e
    LEFT JOIN lineas_accion l ON e.id_linea_accion = l.id
    LEFT JOIN programas p ON e.programa_responsable = p.id_programa
    WHERE e.id_evento = ?
");
$consulta_evento->execute([$id_evento]);
$evento = $consulta_evento->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    die("⚠️ No se encontró información del evento con ID: $id_evento.");
}

// Consulta de asistentes (con o sin filtro de cédula)
$sql_asistentes = "
    SELECT a.cedula, a.nombre_completo, ta.tipo AS tipo_asistente, p.nombre AS programa
    FROM asistencia asi
    INNER JOIN asistentes a ON asi.id_asistente = a.id
    LEFT JOIN tipos_asistentes ta ON a.id_tipo = ta.id_tipo
    LEFT JOIN programas p ON a.id_programa = p.id_programa
    WHERE asi.id_evento = :id_evento
";

$params = [':id_evento' => $id_evento];

if (!empty($f_cedula)) {
    $sql_asistentes .= " AND a.cedula = :cedula";
    $params[':cedula'] = $f_cedula;
}

$sql_asistentes .= " ORDER BY a.nombre_completo ASC";

$consulta_asistentes = $pdo->prepare($sql_asistentes);
$consulta_asistentes->execute($params);
$asistentes = $consulta_asistentes->fetchAll(PDO::FETCH_ASSOC);
$total_asistentes = count($asistentes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include_once("../../componentes/header.php"); HeaderPersonalizado::mostrar(); ?>

<div class="container mt-5">
    <h2 class="mb-4">📋 Detalles del Evento</h2>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($evento['nombre_evento']) ?></h5>
            <p><strong>Fecha:</strong> <?= $evento['fecha_inicio'] ?> a <?= $evento['fecha_final'] ?></p>
            <p><strong>Línea de Acción:</strong> <?= $evento['linea'] ?></p>
            <p><strong>Programa Responsable:</strong> <?= $evento['programa'] ?></p>
            <p><strong>Asistentes Totales:</strong> <?= $total_asistentes ?></p>
            <!-- Botón llamativo para exportar o generar reportes -->
        <div class="text-center mt-3">
            <a href="monitor_generar_reporte_evento_excel.php?id_evento=<?= $id_evento ?>" class="btn btn-primary btn-lg">
                📊 Generar Reporte
            </a>
        </div>
        </div>
    </div>
    

    <!-- FORMULARIO DE BÚSQUEDA POR CÉDULA -->
    <form method="GET" class="row g-3 mb-3">
        <input type="hidden" name="id_evento" value="<?= $id_evento ?>">
        <div class="col-md-6">
            <input type="text" name="cedula" class="form-control" placeholder="Buscar por cédula" value="<?= htmlspecialchars($f_cedula) ?>">
        </div>
        <div class="col-md-6 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="monitor_ver_evento.php?id_evento=<?= $id_evento ?>" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    

    <!-- RESULTADO DE FILTRO -->
    <?php if ($f_cedula): ?>
        <div class="alert alert-info">
            Se encontraron <strong><?= $total_asistentes ?></strong> coincidencia(s) con la cédula <strong><?= htmlspecialchars($f_cedula) ?></strong> en este evento.
        </div>
    <?php endif; ?>

    <!-- LISTADO DE ASISTENTES -->
    <?php if ($total_asistentes === 0): ?>
        <div class="alert alert-warning">😕 No hay asistentes registrados con los criterios especificados.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Programa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($asistentes as $asistente): ?>
                        <tr>
                            <td><?= htmlspecialchars($asistente['cedula']) ?></td>
                            <td><?= htmlspecialchars($asistente['nombre_completo']) ?></td>
                            <td><?= htmlspecialchars($asistente['tipo_asistente']) ?></td>
                            <td><?= htmlspecialchars($asistente['programa']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include_once("../../componentes/footer.php"); Footer::mostrar(); ?>
</body>
</html>

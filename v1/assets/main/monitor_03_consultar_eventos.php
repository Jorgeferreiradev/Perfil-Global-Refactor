<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'monitor') {
    header("Location: ../../login.php");
    exit();
}

require_once('../../conexion.php');
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

// Cargar filtros
$lineas = $pdo->query("SELECT id, nombre FROM lineas_accion")->fetchAll(PDO::FETCH_ASSOC);
$programas = $pdo->query("SELECT id_programa, nombre FROM programas")->fetchAll(PDO::FETCH_ASSOC);

// Capturar filtros del formulario
$f_linea = $_GET['id_linea_accion'] ?? '';
$f_programa = $_GET['id_programa'] ?? '';
$f_cedula = $_GET['cedula'] ?? '';

// ---------------------------
// 1. Consulta de eventos con filtros
// ---------------------------
$sql = "SELECT e.id_evento, e.nombre_evento, e.fecha_inicio, e.fecha_final, l.nombre AS nombre_linea, p.nombre
        FROM eventos e
        LEFT JOIN lineas_accion l ON e.id_linea_accion = l.id
        LEFT JOIN programas p ON e.programa_responsable = p.id_programa
        WHERE 1";

$params = [];

if (!empty($f_linea)) {
    $sql .= " AND e.id_linea_accion = :linea";
    $params[':linea'] = $f_linea;
}

if (!empty($f_programa)) {
    $sql .= " AND e.programa_responsable = :programa";
    $params[':programa'] = $f_programa;
}

if (!empty($f_cedula)) {
    $sql .= " AND e.id_evento IN (
        SELECT asi.id_evento FROM asistencia asi
        INNER JOIN asistentes a ON asi.id_asistente = a.id
        WHERE a.cedula = :cedula
    )";
    $params[':cedula'] = $f_cedula;
}

$sql .= " ORDER BY e.fecha_inicio DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---------------------------
// 2. Consulta por cédula (cuántos eventos ha asistido)
// ---------------------------
$total_asistencias = 0;
if (!empty($f_cedula)) {
    $stmtAsist = $pdo->prepare("
        SELECT COUNT(DISTINCT asi.id_evento) AS total
        FROM asistencia asi
        INNER JOIN asistentes a ON asi.id_asistente = a.id
        WHERE a.cedula = :cedula
    ");
    $stmtAsist->execute([':cedula' => $f_cedula]);
    $resultado = $stmtAsist->fetch(PDO::FETCH_ASSOC);
    $total_asistencias = $resultado['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include_once("../../componentes/header.php");
    HeaderPersonalizado::mostrar(); ?>
    <div class="container mt-5">
        <h2 class="mb-4">📊 Consultar eventos</h2>

        <!-- FORMULARIO DE FILTROS -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Línea de Acción:</label>
                <select name="id_linea_accion" class="form-select">
                    <option value="">-- Todas --</option>
                    <?php foreach ($lineas as $linea): ?>
                        <option value="<?= $linea['id'] ?>" <?= ($f_linea == $linea['id']) ? 'selected' : '' ?>>
                            <?= $linea['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Programa Responsable:</label>
                <select name="id_programa" class="form-select">
                    <option value="">-- Todos --</option>
                    <?php foreach ($programas as $prog): ?>
                        <option value="<?= $prog['id_programa'] ?>" <?= ($f_programa == $prog['id_programa']) ? 'selected' : '' ?>>
                            <?= $prog['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Buscar por Numero Documento:</label>
                <input type="text" name="cedula" class="form-control" placeholder="Ej: 12345678" value="<?= htmlspecialchars($f_cedula) ?>">
            </div>

            <div class="col-12 d-flex justify-content-between mt-3"><center>
                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                <a href="monitor_03_consultar_eventos.php" class="btn btn-secondary">Limpiar</a>
            </div></center>
        </form>
        
        <!-- RESULTADO DE CONSULTA POR CÉDULA -->
        <?php if ($f_cedula): ?>
            <div class="alert alert-info">
                📌 La cédula <strong><?= htmlspecialchars($f_cedula) ?></strong> ha asistido a <strong><?= $total_asistencias ?></strong> evento(s) este semestre.
            </div>
        <?php endif; ?>

        <!-- TABLA DE EVENTOS -->
        <?php if (empty($eventos)): ?>
            <div class="alert alert-warning">No se encontraron eventos con los filtros seleccionados.</div>
        <?php else: ?>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Línea de Acción</th>
                        <th>Programa</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $e): ?>
                        <tr>
                            <td><?= $e['nombre_evento'] ?></td>
                            <td><?= $e['fecha_inicio'] ?> - <?= $e['fecha_final'] ?></td>
                            <td><?= $e['nombre_linea'] ?></td>
                            <td><?= $e['nombre'] ?></td>
                            <td>
                                <a href="monitor_ver_evento.php?id_evento=<?= $e['id_evento'] ?>" class="btn btn-primary">Ver evento</a>
                                <a href="monitor_modificar_evento.php?id_evento=<?= $e['id_evento'] ?>" class="btn btn-warning">Modificar</a>
                                
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

<?php include_once("../../componentes/footer.php");
Footer::mostrar(); ?>
</html>

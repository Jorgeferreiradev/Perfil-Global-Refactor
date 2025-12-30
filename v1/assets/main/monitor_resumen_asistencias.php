<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'monitor') {
    http_response_code(403);
    echo "<div class='alert alert-danger text-center'>❌ Acceso denegado.</div>";
    exit();
}

require_once('../../conexion.php');
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();
$id_evento = intval($_GET['id_evento'] ?? 0);

if (!$id_evento) {
    echo "<div class='alert alert-warning text-center'>⚠️ Evento no válido.</div>";
    exit();
}

$sql = "
    SELECT a.cedula, a.nombre_completo, a.ano, a.semestre, ta.tipo AS tipo_asistente, p.nombre AS programa
    FROM asistencia asi
    INNER JOIN asistentes a ON asi.id_asistente = a.id
    INNER JOIN tipos_asistentes ta ON a.id_tipo = ta.id_tipo
    INNER JOIN programas p ON a.id_programa = p.id_programa
    WHERE asi.id_evento = ?
    GROUP BY a.cedula
    ORDER BY a.nombre_completo ASC
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $id_evento, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h2 class="text-center mb-4">📊 Resumen de Asistencias</h2>
    <?php if (!empty($result)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre completo</th>
                        <th>Tipo</th>
                        <th>Programa</th>
                        <th>Año</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['cedula']); ?></td>
                            <td><?= htmlspecialchars($row['nombre_completo']); ?></td>
                            <td><?= htmlspecialchars($row['tipo_asistente']); ?></td>
                            <td><?= htmlspecialchars($row['programa']); ?></td>
                            <td><?= htmlspecialchars($row['ano'] ?? 'No registrado'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No hay asistentes registrados para este evento.</div>
    <?php endif; ?>
</div>
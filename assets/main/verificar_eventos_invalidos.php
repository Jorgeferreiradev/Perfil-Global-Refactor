<?php
require_once('../../conexion.php');
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['corregir'])) {
    $stmt = $pdo->prepare("
        UPDATE eventos 
        SET 
            modalidad = IFNULL(modalidad, 'Presencial'), 
            nivel_academico = IFNULL(nivel_academico, 'Tecnologico') 
        WHERE modalidad IS NULL OR nivel_academico IS NULL
    ");
    $stmt->execute();
    echo "<script>alert('Eventos corregidos exitosamente'); window.location.href=window.location.href;</script>";
    exit;
}

$query = "
    SELECT id_evento, nombre_evento, modalidad, nivel_academico
    FROM eventos
    WHERE modalidad IS NULL OR nivel_academico IS NULL
";
$result = $pdo->query($query);
$eventos_invalidos = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de Eventos Inválidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h3 class="mb-4">🛠 Verificación de Campos Nulos en la Tabla <code>eventos</code></h3>

    <?php if (count($eventos_invalidos) > 0): ?>
        <div class="alert alert-danger">
            <strong>¡Atención!</strong> Hay <?= count($eventos_invalidos) ?> eventos con campos nulos (modalidad o nivel académico).
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Evento</th>
                    <th>Modalidad</th>
                    <th>Nivel Académico</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eventos_invalidos as $evento): ?>
                    <tr>
                        <td><?= $evento['id_evento'] ?></td>
                        <td><?= $evento['nombre_evento'] ?></td>
                        <td><?= $evento['modalidad'] ?? '<span class="text-danger">NULL</span>' ?></td>
                        <td><?= $evento['nivel_academico'] ?? '<span class="text-danger">NULL</span>' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form method="post">
            <button type="submit" name="corregir" class="btn btn-success">✅ Corregir todos automáticamente</button>
        </form>

    <?php else: ?>
        <div class="alert alert-success">
            ✅ No hay eventos con campos nulos. Todo está en orden.
        </div>
    <?php endif; ?>
</body>
</html>

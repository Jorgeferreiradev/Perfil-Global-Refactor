<?php
require_once('../../conexion.php');

$cedula = $_GET['cedula'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Invitado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Registrar asistente invitado</h3>
    <form method="POST" action="guardar_invitado.php">
        <div class="mb-3">
            <label for="cedula" class="form-label">Cédula</label>
            <input type="text" name="cedula" id="cedula" value="<?= htmlspecialchars($cedula) ?>" class="form-control" required readonly>
        </div>
        <div class="mb-3">
            <label for="nombre_completo" class="form-label">Nombre completo</label>
            <input type="text" name="nombre_completo" id="nombre_completo" class="form-control" required>
        </div>
        <input type="hidden" name="id_programa" value="1"> <!-- ejemplo: programa genérico -->
        <input type="hidden" name="id_tipo" value="99"> <!-- ejemplo: tipo "invitado" -->
        <input type="hidden" name="ano" value="2025">
        <input type="hidden" name="semestre" value="II">
        <button type="submit" class="btn btn-success">Guardar invitado</button>
    </form>
</body>
</html>

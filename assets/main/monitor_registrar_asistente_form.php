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

// Obtener cédula y evento por GET
$cedula_get = isset($_GET['cedula']) ? htmlspecialchars($_GET['cedula']) : '';
$id_evento = isset($_GET['evento']) ? intval($_GET['evento']) : 0;

$error = '';
$success = '';

// Guardar asistente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');
    $nombre = trim($_POST['nombre_completo'] ?? '');
    $id_tipo = intval($_POST['id_tipo'] ?? 0);
    $id_programa = intval($_POST['id_programa'] ?? 0);
    $ano = date('Y');
    $semestre = date('n') <= 6 ? 1 : 2;

    if (empty($cedula) || empty($nombre) || $id_tipo <= 0 || $id_programa <= 0) {
        $error = "Todos los campos son obligatorios.";
    } else {
        $stmtCheck = $pdo->prepare("SELECT id FROM asistentes WHERE cedula = :cedula");
        $stmtCheck->execute(['cedula' => $cedula]);
        if ($stmtCheck->fetch()) {
            $error = "La cédula ya está registrada.";
        } else {
            $stmtInsert = $pdo->prepare("INSERT INTO asistentes (cedula, nombre_completo, id_tipo, id_programa, ano, semestre)
            VALUES (:cedula, :nombre_completo, :id_tipo, :id_programa, :ano, :semestre)");
            $stmtInsert->execute([
                'cedula' => $cedula,
                'nombre_completo' => $nombre,
                'id_tipo' => $id_tipo,
                'id_programa' => $id_programa,
                'ano' => $ano,
                'semestre' => $semestre
            ]);

            // Redirigir al módulo de asistencia
            if ($id_evento > 0) {
                header("Location: monitor_02_registro_asistente.php?id_evento={$id_evento}&nuevo={$cedula}");
                exit;
            }

            $success = "Asistente registrado correctamente.";
        }
    }
}
?>

    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Asistente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-4">Registrar Nuevo Asistente</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" name="cedula" id="cedula" class="form-control" required
                        value="<?= $cedula_get ?>" <?= $cedula_get ? 'readonly' : '' ?>>
                </div>

                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Nombre Completo</label>
                    <input type="text" name="nombre_completo" id="nombre_completo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="id_tipo" class="form-label">Tipo de Asistente</label>
                    <select name="id_tipo" id="id_tipo" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="3">Administrativo</option>
                        <option value="4">Egresado</option>
                        <option value="5">Invitado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_programa" class="form-label">Programa Académico</label>
                    <select name="id_programa" id="id_programa" class="form-select" required>
                        <option value="">Seleccione</option>
                        <?php
                        $programas = $pdo->query("SELECT id_programa, nombre FROM programas ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($programas as $programa) {
                            echo "<option value=\"{$programa['id_programa']}\">" . htmlspecialchars($programa['nombre']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="ano" class="form-label">Año</label>
                    <input type="text" name="ano" id="ano" class="form-control" value="<?= date('Y') ?>" readonly>  
                </div>
                <div class="mb-3">
                    <label for="semestre" class="form-label">Semestre</label>
                    <input type="text" name="semestre" id="semestre" class="form-control" value="<?= date('n') <= 6 ? '1' : '2' ?>" readonly>   
                </div>


                <button type="submit" class="btn btn-primary">Registrar Asistente</button>
                <a href="monitor_02_registro_asistente.php<?= $id_evento ? '?id_evento=' . $id_evento : '' ?>" class="btn btn-secondary ms-2">Volver</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>


</div>

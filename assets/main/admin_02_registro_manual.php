<?php
session_start();

// Acceso restringido al rol administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../../login.php");
    exit();
}

require_once("../../conexion.php");
$db   = new ConexionDB();
$conn = $db->obtenerConexion();

// Obtener datos para selects
$programas = $conn->query("SELECT id_programa, nombre FROM programas")->fetchAll(PDO::FETCH_ASSOC);
$tipos     = $conn->query("SELECT id_tipo, tipo FROM tipos_asistentes")->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula']);
    $nombre = trim($_POST['nombre']);
    $prog   = $_POST['programa'] ?? '';
    $tipo   = $_POST['tipo'] ?? '';
    $ano    = $_POST['ano'] ?? '';
    $sem    = $_POST['semestre'] ?? '';

    // Validaciones
    if (empty($cedula)) $errors[] = "Cédula es obligatoria.";
    if (empty($nombre)) $errors[] = "Nombre es obligatorio.";
    if (!preg_match('/^\d{4}$/', $ano)) $errors[] = "Año inválido.";
    if (!in_array($sem, ['I', 'II'])) $errors[] = "Semestre inválido.";

    // Validar programa y tipo
    $programaExiste = in_array($prog, array_column($programas, 'id_programa'));
    $tipoExiste = in_array($tipo, array_column($tipos, 'id_tipo'));

    if (!$programaExiste) $errors[] = "Programa inválido.";
    if (!$tipoExiste) $errors[] = "Tipo de asistente inválido.";

    // Evitar duplicados
    $check = $conn->prepare("SELECT cedula FROM asistentes WHERE cedula = ?");
    $check->execute([$cedula]);

    if ($check->rowCount() > 0) {
        $errors[] = "Ya existe un asistente con esa cédula.";
    }

    // Insertar si no hay errores
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO asistentes (cedula, nombre_completo, id_programa, id_tipo, ano, semestre) 
                                    VALUES (:cedula, :nombre, :programa, :tipo, :ano, :semestre)");
            $stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':programa', $prog, PDO::PARAM_INT);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
            $stmt->bindParam(':ano', $ano, PDO::PARAM_STR);
            $stmt->bindParam(':semestre', $sem, PDO::PARAM_STR);
            $stmt->execute();
            $success = true;
        } catch (PDOException $e) {
            $errors[] = "Error al guardar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Manual de Asistente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include_once("../../componentes/header.php"); HeaderPersonalizado::mostrar(); ?>

<main class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-4">Registro Manual de Asistente</h3>

            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">Asistente registrado exitosamente.</div>
            <?php endif; ?>

            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Numero de Documento</label>
                        <input type="text" name="cedula" class="form-control" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Programa Académico</label>
                        <select name="programa" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($programas as $p): ?>
                                <option value="<?= $p['id_programa'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipo de Asistente</label>
                        <select name="tipo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($tipos as $t): ?>
                                <option value="<?= $t['id_tipo'] ?>"><?= htmlspecialchars($t['tipo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Año</label>
                        <select name="ano" class="form-select" required>
                            <option value="<?= date('Y') ?>" selected><?= date('Y') ?></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Semestre</label>
                        <select name="semestre" class="form-select" required>
                            <option value="">--</option>
                            <option value="I">I (Ene-Jun)</option>
                            <option value="II">II (Jul-Dic)</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary"><i class="fa fa-save"></i> Guardar Asistente</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
$("#cedula").on("input", function () {
    const cedula = $(this).val().trim();

    if (cedula.length >= 6) {
        $.get("assets/main/buscar_asistente.php", { cedula }, function (respuesta) {
            if (typeof respuesta === "string") {
                respuesta = JSON.parse(respuesta);
            }

            if (respuesta.existe) {
                $("#resumen_asistente").html(`
                    <div class="alert alert-success">
                        <strong>✅ Asistente encontrado:</strong><br>
                        <strong>Nombre:</strong> ${respuesta.datos.nombre_completo}<br>
                        <strong>Programa:</strong> ${respuesta.datos.id_programa}<br>
                        <strong>Tipo:</strong> ${respuesta.datos.id_tipo}<br>
                        <strong>Año:</strong> ${respuesta.datos.ano} - <strong>Semestre:</strong> ${respuesta.datos.semestre}
                    </div>
                `);
            } else {
                $("#resumen_asistente").html(`
                    <div class="alert alert-warning">
                        ${respuesta.mensaje}<br>
                        <a href="registrar_invitado.php?cedula=${cedula}" class="btn btn-sm btn-primary mt-2">Registrar como invitado</a>
                    </div>
                `);
            }
        });
    } else {
        $("#resumen_asistente").empty();
    }
});
</script>

<script>
$(document).ready(function () {
    $("#cedula").on("input", function () {
        const cedula = $(this).val().trim();

        if (cedula.length >= 6) {
            $.get("buscar_asistente.php", { cedula }, function (respuesta) {
                respuesta = JSON.parse(respuesta); // Asegura que sea objeto JS

                if (respuesta.existe) {
                    $("#resumen_asistente").html(`
                        <div class="alert alert-success">
                            ✅ Asistente encontrado:<br>
                            Nombre: ${respuesta.datos.nombre_completo}<br>
                            Email: ${respuesta.datos.email || 'No registrado'}
                        </div>
                    `);
                } else {
                    $("#resumen_asistente").html(`
                        <div class="alert alert-warning">
                            ${respuesta.mensaje}<br>
                            <a href="registrar_invitado.php?cedula=${cedula}" class="btn btn-sm btn-primary mt-2">
                                Registrar como invitado
                            </a>
                        </div>
                    `);
                }
            });
        } else {
            $("#resumen_asistente").empty();
        }
    });
});
</script>


<script>
    setTimeout(() => {
        document.querySelectorAll(".alert").forEach(el => {
            el.style.opacity = "0";
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);
</script>

<?php include_once("../../componentes/footer.php"); Footer::mostrar(); ?>

</body>
</html>
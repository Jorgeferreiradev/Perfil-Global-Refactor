<?php
session_start();

// Verificar si el usuario es un monitor
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'monitor') {
    header("Location: ../../login.php");
    exit();
}

// Incluir componentes y conexión
require_once("../../componentes/header.php");
require_once("../../componentes/footer.php");
require_once("../../conexion.php");

// Crear instancia de conexión
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

// Obtener datos para los selects
$lineas_accion = $pdo->query("SELECT id, nombre FROM lineas_accion")->fetchAll(PDO::FETCH_ASSOC);
$programas = $pdo->query("SELECT id_programa, nombre FROM programas")->fetchAll(PDO::FETCH_ASSOC);

// Mensaje de retroalimentación
$mensaje = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $sql = "INSERT INTO eventos 
                (nombre_evento, id_linea_accion, ano, semestre, fecha_inicio, fecha_final, sede, programa_responsable)
                VALUES 
                (:nombre_evento, :id_linea_accion, :ano, :semestre, :fecha_inicio, :fecha_final, :sede, :programa_responsable)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre_evento' => $_POST['nombre_evento'],
            ':id_linea_accion' => $_POST['id_linea_accion'],
            ':ano' => $_POST['ano'],
            ':semestre' => $_POST['semestre'],
            ':fecha_inicio' => $_POST['fecha_inicio'],
            ':fecha_final' => $_POST['fecha_final'],
            ':sede' => $_POST['sede'],
            ':programa_responsable' => $_POST['programa_responsable']
        ]);
        $mensaje = "✅ Evento creado exitosamente.";
    } catch (PDOException $e) {
        $mensaje = "❌ Error al crear evento: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 700px;
            margin: auto;
        }
    </style>
</head>
<body class="bg-light">
    <?php HeaderPersonalizado::mostrar(); ?>

    <div class="container mt-5 form-container">
        <h2 class="mb-4 text-center">📅 Crear nuevo evento</h2>

        <?php if ($mensaje): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Nombre del Evento</label>
                    <input type="text" name="nombre_evento" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Línea de Acción</label>
                    <select name="id_linea_accion" class="form-select" required>
                        <option value="">Seleccione una línea</option>
                        <?php foreach ($lineas_accion as $linea): ?>
                            <option value="<?= $linea['id'] ?>"><?= htmlspecialchars($linea['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Programa Responsable</label>
                    <select name="programa_responsable" class="form-select" required>
                        <option value="">Seleccione un programa</option>
                        <?php foreach ($programas as $programa): ?>
                            <option value="<?= $programa['id_programa'] ?>"><?= htmlspecialchars($programa['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Año</label>
                    <select name="ano" class="form-select" required>
                        <option value="<?= date('Y') ?>" selected><?= date('Y') ?></option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Semestre</label>
                    <select name="semestre" class="form-select" required>
                        <option value="I">I (Enero-Junio)</option>
                        <option value="II">II (Julio-Diciembre)</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha Final</label>
                    <input type="date" name="fecha_final" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Sede</label>
                    <select name="sede" class="form-select" required>
                        <option value="Cúcuta">Cúcuta</option>
                        <option value="Ocaña">Ocaña</option>
                    </select>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">Guardar Evento</button>
                <a href="monitor_02_registro_asistente.php" class="btn btn-outline-success px-4">➕ Registrar Asistentes</a>
                <a href="monitor_03_consultar_eventos.php" class="btn btn-outline-secondary px-4">🔍 Consultar Eventos</a>
            </div>
        </form>
    </div>

    <script>
        setTimeout(() => {
            document.querySelectorAll(".alert").forEach(el => {
                el.style.opacity = "0";
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    </script>

    <?php Footer::mostrar(); ?>
</body>
</html>
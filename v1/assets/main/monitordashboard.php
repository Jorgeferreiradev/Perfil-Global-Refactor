<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verifica si hay un usuario autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../login.php");
    exit();
}

// Incluir el header para que la clase HeaderPersonalizado esté disponible

include_once("../../componentes/header.php");  

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil Global - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Estilos propios -->
    <link rel="stylesheet" href="../css/dashbd.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">



</head>
<body>
    <?php HeaderPersonalizado::mostrar(); ?>

    <main class="container my-4">
        <center><h1 class="mb-4">Panel Principal del Monitor</h1></center>
        <div class="row g-8">
            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <span class="icon fs-1">📅</span>
                    <a href="../main/monitor_01_crear_evento.php" class="stretched-link text-decoration-none fw-semibold">Crear Eventos</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <span class="icon fs-1">📝</span>
                    <a href="../main/monitor_02_registro_asistente.php" class="stretched-link text-decoration-none fw-semibold">Registrar Asistencias</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <span class="icon fs-1">🔍</span>
                    <a href="../main/monitor_03_consultar_eventos.php" class="stretched-link text-decoration-none fw-semibold">Filtros y Gestion de Asistencias</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <span class="icon fs-1">📊</span>
                    <a href="../main/monitor_04_reportes_eventos.php" class="stretched-link text-decoration-none fw-semibold">Creacion de Reportes</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <span class="icon fs-1">📑</span>
                    <a href="../main/monitor_05_reporte_semestral.php" class="stretched-link text-decoration-none fw-semibold">Reporte Semestral</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <span class="icon fs-1">✉️</span>
                    <a href="sugerencias.php" class="stretched-link text-decoration-none fw-semibold">Novedades</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
<?php include_once("../../componentes/footer.php");
Footer::mostrar();?>
</html>
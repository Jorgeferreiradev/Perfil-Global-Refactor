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
include_once("../../componentes/footer.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil Global - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Estilos propios -->
    <link rel="stylesheet" href="../css/dashbd.css">

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <head>

</head>
</head>
<body>
    <?php HeaderPersonalizado::mostrar();?>

    <main class="container my-4">
        <h1 class="mb-4">Panel Principal del Administrador</h1>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow text-center p-4">
                    <span class="icon fs-1">📤</span>
                    <a href="../main/admin_01_cargar_bd.php" class="stretched-link text-decoration-none fw-semibold">Subir Bloque Semestral de Usuarios (Excel)</a>
                    
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow text-center p-4">
                    <span class="icon fs-1">📋</span>
                    <a href="../main/admin_02_registro_manual.php" class="stretched-link text-decoration-none fw-semibold">Registrar Nuevo Usuario</a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow text-center p-4">
                    <span class="icon fs-1">⚙️</span>
                    <a href="../main/admin_03_gestion_asistentes.php" class="stretched-link text-decoration-none fw-semibold">Modificar Usuarios</a>
                </div>
            </div>



            <div class="col-md-6">
                <div class="card shadow text-center p-4">
                    <span class="icon fs-1">✉️</span>
                    <a href="../main/sugerencias.php" class="stretched-link text-decoration-none fw-semibold">Novedades</a>
                </div>
            </div>
        </div>
    </main>
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
<br> <br>
<?php Footer::mostrar();?>
</html>
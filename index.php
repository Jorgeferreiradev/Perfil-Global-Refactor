<?php
session_start();

// Redirigir usuarios autenticados al dashboard correspondiente
if (isset($_SESSION["rol"])) {
    if ($_SESSION["rol"] === "admin") {
        header("Location: assets/main/admindashboard.php");
        exit();
    } elseif ($_SESSION["rol"] === "monitor") {
        header("Location: assets/main/monitordashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil Global - Bienvenido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

 
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .hero {
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        .hero h1 {
            font-weight: 600;
        }
        .hero p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: auto;
        }
        .btn-custom {
            font-size: 1.2rem;
            padding: 10px 20px;
        }
        .panel-section {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        .panel-box {
            width: 45%;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .panel-box h2 {
            font-weight: 600;
        }
        .panel-box .icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        @media (max-width: 768px) {
            .panel-box {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <h1>Bienvenido a Perfil Global</h1>
            <p>Gestiona eventos, asistentes y reportes de manera eficiente.</p>
        </div>
    </header>

    <!-- Sección de selección de roles -->
    <section class="container mt-5">
        <div class="panel-section">
            <!-- Panel Admin -->
            <div class="panel-box">
                <span class="icon text-primary"><i class="fa-solid fa-user-shield"></i></span>
                <h2>Administradores</h2>
                <p>Cargar bases de datos, agrega o modifica usuarios y configura el sistema.</p>
                <a href="login.php?rol=admin" class="btn btn-primary btn-lg mt-3">
                    <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión como Admin
                </a>
            </div>

            <!-- Panel Monitor -->
            <div class="panel-box">
                <span class="icon text-success"><i class="fa-solid fa-user-tie"></i></span>
                <h2>Monitores</h2>
                <p>Registra asistentes, organiza eventos en tiempo real y exporta informes Excel y PDF.</p>
                <a href="login.php?rol=monitor" class="btn btn-success btn-lg mt-3">
                    <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión como Monitor
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include_once("componentes/footer.php"); ?>
    <?php Footer::mostrar(); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
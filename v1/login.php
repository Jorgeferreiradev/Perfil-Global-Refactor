<?php
session_start();
include("conexion.php");

// Crear conexión con PDO
$db = new ConexionDB();
$conn = $db->obtenerConexion();

$error = "";

// Si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["usuario"]);
    $contrasena = trim($_POST["contrasena"]);

    // Preparar y ejecutar consulta
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fila) {
        // Comparar contraseñas (sin cifrado)
        if ($contrasena === $fila["contrasena"]) {
            $_SESSION["usuario"] = $fila["usuario"];
            $_SESSION["rol"] = $fila["rol"];

            // Redirige según el rol
            if ($fila["rol"] === "administrador") {
                header("Location: ./assets/main/admindashboard.php");
                exit();
            } elseif ($fila["rol"] === "monitor") {
                header("Location: ./assets/main/monitordashboard.php");
                exit();
            } else {
                $error = "Rol no válido. Contacta al administrador.";
            }
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>


<!-- HTML para el formulario de inicio de sesión -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de Sesión</title>

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">


    <!-- diseño y estilo -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
        }
        header, nav {
            background-color: #ffffff !important;
        }
        .card {
            border: none;
            border-radius: 1rem;
        }
        .card-body {
            padding: 2rem;
        }
        .btn i {
            margin-right: 0.5rem;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
    </head>

    
<!-- Cuerpo del documento -->
<body>
    <!-- Header con botones de navegación -->
    <header class="py-2 shadow-sm">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a class="navbar-brand fw-semibold" href="index.php">
                        <i class="fa-solid fa-house"></i> Volver al Panel
                    </a>
                </div>
                <div>
                    <a href="logout.php" class="btn btn-outline-danger me-2">
                        <i class="fa-solid fa-power-off"></i> Cerrar Sesión
                    </a>
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="fa-solid fa-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenedor del formulario de inicio de sesión -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Inicio de Sesión</h2>
                        
                        <?php if ($error != ""): ?>
                            <div class="error"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <!-- El action apunta a este mismo archivo para procesar la validación -->
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" name="usuario" id="usuario" placeholder="Usuario" required class="form-control">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required class="form-control">
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-right-to-bracket"></i> Ingresar
                                </button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <a href="#" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<?php include_once("componentes/footer.php");
Footer::mostrar();?>
</html>

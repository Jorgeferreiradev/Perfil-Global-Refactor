<?php
class HeaderPersonalizado {
    public static function mostrar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si la sesión contiene datos del usuario
        if (!isset($_SESSION["usuario"]) || !isset($_SESSION["rol"])) {
            $_SESSION["usuario"] = "Invitado";
            $_SESSION["rol"] = "sin rol";
        }

        // Asignar variables con datos de sesión correctamente
        $usuario = htmlspecialchars($_SESSION["usuario"]);
        $rol = strtolower($_SESSION["rol"]); 

        // Determinar el dashboard de destino
        $dashboard_url = ($rol === "administrador") ? "admindashboard.php" : (($rol === "monitor") ? "monitordashboard.php" : "#");

        echo '
        <header class="py-2 shadow-sm bg-white">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a class="navbar-brand fw-semibold" href="' . $dashboard_url . '">
                            <i class="fa-solid fa-house"></i> Volver al Panel
                        </a>
                        <span class="ms-3 fw-normal text-muted">
                            <i class="fa-solid fa-user"></i> ' . ucfirst($usuario) . ' (' . ucfirst($rol) . ')
                        </span>
                    </div>
                    <div>
                        <a href="../../logout.php" class="btn btn-outline-danger me-2">
                            <i class="fa-solid fa-power-off"></i> Cerrar Sesión
                        </a>
                        <button onclick="window.history.back();" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Regresar
                        </button>
                    </div>
                </div>
            </div>
        </header>
        ';
    }
}
?>
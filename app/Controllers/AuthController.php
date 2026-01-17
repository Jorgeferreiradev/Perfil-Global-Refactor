<?php
namespace App\Controllers;

use App\Models\Usuario;

class AuthController {
    
    public function login() {
        // Si ya hay sesión, mandarlo al dashboard directamente
        if (isset($_SESSION['user_id'])) {
            header('Location: admin/dashboard');
            exit;
        }
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new Usuario();
            $user = $userModel->buscarPorEmail($correo);

            // Verificamos si existe el usuario y si la clave (hash) coincide
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombres'];
                $_SESSION['rol'] = $user['rol'];

                // Redirigimos al dashboard
                header('Location: admin/dashboard');
                exit;
            } else {
                $_SESSION['error'] = "Correo o contraseña incorrectos.";
                header('Location: login');
                exit;
            }
        }
    }
}
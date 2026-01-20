<?php
namespace App\Controllers;

use App\Models\Usuario;

class AuthController {
    
    public function showLogin() {
        // Si ya está logueado, enviar al dashboard
        if (isset($_SESSION['user_id'])) {
            // 1. CAMBIO: Usamos BASE_URL para redirigir bien
            header('Location: ' . BASE_URL . '/dashboard'); // <--- CAMBIO AQUÍ
            exit;
        }
        require_once __DIR__ . '/../../resources/views/auth/login.php';
    }

    public function login() {
    $email = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    $usuarioModel = new Usuario();
    $user = $usuarioModel->findByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        // 1. Guardar sesión básica
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nombre'] = $user['nombres'] . ' ' . $user['apellidos'];
        $_SESSION['user_rol'] = $user['rol']; // Solo será 'admin' o 'monitor'
        $_SESSION['last_activity'] = time();

        // 2. Redirigir al Dashboard Único
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    } else {
        $_SESSION['error'] = 'Credenciales inválidas';
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}

    public function logout() {
        // 3. Destruir sesión   
        session_unset();
        session_destroy();
        
        // 4. CAMBIO: Al salir, ir al login correcto
        header('Location: ' . BASE_URL . '/login'); // <--- CAMBIO AQUÍ
        exit;
    }
}
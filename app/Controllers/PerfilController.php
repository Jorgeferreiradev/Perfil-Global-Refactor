<?php
namespace App\Controllers;

use App\Models\Usuario;

class PerfilController {

    public function index() {
        // Obtenemos el ID del usuario desde la sesión
        $userId = $_SESSION['user_id'];
        
        $modelo = new Usuario();
        $usuario = $modelo->getById($userId);

        // Cargamos la vista
        require_once __DIR__ . '/../../resources/views/perfil/index.php';
    }

    public function update() {
        $userId = $_SESSION['user_id'];
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $password = !empty($_POST['password']) ? $_POST['password'] : null;
        $confirm = !empty($_POST['confirm_password']) ? $_POST['confirm_password'] : null;

        // Validación de contraseñas iguales
        if ($password && $password !== $confirm) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Las contraseñas no coinciden.'];
            header('Location: ' . BASE_URL . '/perfil');
            exit;
        }

        $modelo = new Usuario();
        if ($modelo->updatePerfil($userId, $nombres, $apellidos, $password)) {
            // Actualizamos el nombre en la sesión para que se refresque en el header
            $_SESSION['user_nombre'] = $nombres . ' ' . $apellidos;
            
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Perfil actualizado correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Error al actualizar el perfil.'];
        }

        header('Location: ' . BASE_URL . '/perfil');
    }
}
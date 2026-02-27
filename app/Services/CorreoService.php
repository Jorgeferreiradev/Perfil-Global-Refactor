<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CorreoService {
    
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        // CONFIGURACIÓN DEL SERVIDOR (Usa Gmail o tu hosting)
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com'; // O smtp.hostinger.com
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'tu_correo@gmail.com'; // <--- CAMBIA ESTO
        $this->mail->Password   = 'tu_contraseña_aplicacion'; // <--- CAMBIA ESTO
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = 587;
        
        $this->mail->setFrom('no-reply@perfilglobal.com', 'Soporte Perfil Global');
        $this->mail->isHTML(true);
        $this->mail->CharSet = 'UTF-8';
    }

    public function enviarCredenciales($correo, $nombre, $password) {
        try {
            $this->mail->addAddress($correo, $nombre);
            $this->mail->Subject = 'Bienvenido a Perfil Global V2 - Credenciales de Acceso';
            
            $cuerpo = "
                <h1>¡Hola, $nombre!</h1>
                <p>Se ha creado (o actualizado) tu cuenta en el sistema Perfil Global.</p>
                <p><strong>Tus credenciales son:</strong></p>
                <ul>
                    <li>Usuario: $correo</li>
                    <li>Contraseña: <strong>$password</strong></li>
                </ul>
                <p>Por favor ingresa y cambia tu contraseña lo antes posible.</p>
                <a href='" . BASE_URL . "/login'>Iniciar Sesión</a>
            ";

            $this->mail->Body = $cuerpo;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Loguear error si es necesario: $this->mail->ErrorInfo;
            return false;
        }
    }
}
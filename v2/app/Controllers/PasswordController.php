// Ejemplo en app/Controllers/PasswordController.php
namespace App\Controllers;

use App\Models\Token;
use App\Models\Usuario;

class PasswordController {
    public function procesarRecuperacion() {
        // 1. Validar correo
        // 2. Generar Token
        // 3. Guardar vía Model Token
        // 4. Enviar Email
    }
}
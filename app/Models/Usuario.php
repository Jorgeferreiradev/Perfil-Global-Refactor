<?php
namespace App\Models;

use Config\Database;
use PDO;

class Usuario {

    private $pdo;
    private $table = 'usuarios_sistema';

    public function __construct() {
        // ✔ YA ES PDO
        $this->pdo = Database::getInstance();
    }



    // Trae a todos para que el Superadmin pueda ver a los inactivos y reactivarlos
    public function getAllExcept($id) {
        // 🔥 EXCLUSIÓN DE FANTASMA: Filtramos el ID del desarrollador (ID: 1)
        // Además de no verte a ti mismo, el sistema ignora al usuario raíz.
        $sql = "SELECT * FROM {$this->table} 
                WHERE id != :id 
                AND id != 1 
                ORDER BY rol ASC, nombres ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // 2. NUEVO: Función "Interruptor" (Toggle)
    // Alterna entre activo (NULL) e inactivo (FECHA)
    public function toggleEstado($id) {
        // Si tiene fecha, lo pone en NULL (Activa). Si es NULL, le pone fecha (Desactiva).
        $sql = "UPDATE usuarios_sistema SET deleted_at = IF(deleted_at IS NULL, NOW(), NULL) WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }


    public function create($data) {
        $sql = "INSERT INTO {$this->table} (nombres, apellidos, correo, password, rol) 
                VALUES (:nom, :ape, :cor, :pass, :rol)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom'  => $data['nombres'],
            ':ape'  => $data['apellidos'],
            ':cor'  => $data['correo'],
            ':pass' => $data['password'],
            ':rol'  => $data['rol']
        ]);
    }

    public function exists($correo) {
        $sql = "SELECT id FROM {$this->table} WHERE correo = :cor LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cor' => $correo]);
        return $stmt->fetch();
    }

    public function getByCorreo($correo) {
        $sql = "SELECT * FROM {$this->table} WHERE correo = :cor AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cor' => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function softDelete($id) {
        $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

   public function countAll() {
        // 
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE deleted_at IS NULL 
                AND id != 1";
        
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] : 0;
    }

    /* =========================
       🔥 MÉTODOS NUEVOS (PDO)
    ========================== */

    // ✔ Buscar por ID (Perfil)
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // NUEVA FUNCIÓN: Busca al usuario sin importar si está activo o inactivo
    public function getByIdAll($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✔ Actualizar Perfil (con o sin password)
    public function updatePerfil($id, $nombres, $apellidos, $password = null) {

        if ($password) {
            $sql = "UPDATE {$this->table}
                    SET nombres = :nom, apellidos = :ape, password = :pass
                    WHERE id = :id";

            $params = [
                ':nom'  => $nombres,
                ':ape'  => $apellidos,
                ':pass' => password_hash($password, PASSWORD_BCRYPT),
                ':id'   => $id
            ];
        } else {
            $sql = "UPDATE {$this->table}
                    SET nombres = :nom, apellidos = :ape
                    WHERE id = :id";

            $params = [
                ':nom' => $nombres,
                ':ape' => $apellidos,
                ':id'  => $id
            ];
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // MÉTODO PARA EL ADMIN: Actualizar usuario completo
    public function update($id, $data) {
        // Construimos la SQL dinámica dependiendo si hay contraseña nueva o no
        if (!empty($data['password'])) {
            $sql = "UPDATE {$this->table} SET 
                    nombres = :nom, 
                    apellidos = :ape, 
                    correo = :cor, 
                    rol = :rol,
                    password = :pass
                    WHERE id = :id";
            
            $params = [
                ':nom'  => $data['nombres'],
                ':ape'  => $data['apellidos'],
                ':cor'  => $data['correo'],
                ':rol'  => $data['rol'],
                ':pass' => password_hash($data['password'], PASSWORD_BCRYPT),
                ':id'   => $id
            ];
        } else {
            // Si no mandó password, no lo tocamos
            $sql = "UPDATE {$this->table} SET 
                    nombres = :nom, 
                    apellidos = :ape, 
                    correo = :cor, 
                    rol = :rol
                    WHERE id = :id";
            
            $params = [
                ':nom'  => $data['nombres'],
                ':ape'  => $data['apellidos'],
                ':cor'  => $data['correo'],
                ':rol'  => $data['rol'],
                ':id'   => $id
            ];
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
    // Validar duplicado de correo EXCLUYENDO al usuario actual (para edición)
    public function existsEmailExcept($correo, $id) {
        $sql = "SELECT id FROM {$this->table} WHERE correo = :cor AND id != :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cor' => $correo, ':id' => $id]);
        return $stmt->fetch();
    }

    /* ==========================================
       MÉTODOS DE RECUPERACIÓN DE CONTRASEÑA
       (Pégalos al final de app/Models/Usuario.php)
    ========================================== */

    // 1. Guardar el token y la fecha de expiración
    public function saveResetToken($id, $token, $expires) {
        $sql = "UPDATE {$this->table} 
                SET reset_token = :token, reset_expires = :exp 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':token' => $token, 
            ':exp' => $expires, 
            ':id' => $id
        ]);
    }

    // 2. Buscar usuario por el token (EL QUE TE FALTABA)
    public function getByToken($token) {
        // Buscamos al usuario que tenga ese token EXACTO
        $sql = "SELECT * FROM {$this->table} 
                WHERE reset_token = :token 
                LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Limpiar el token después de usarlo (para que no se use dos veces)
    public function clearResetToken($id) {
        $sql = "UPDATE {$this->table} 
                SET reset_token = NULL, reset_expires = NULL 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }


}

<?php
session_start();

if ($_SESSION["rol"] !== "Admin") {
    die("Acceso denegado.");
}

require_once("../../conexion.php");
$db   = new ConexionDB();
$conn = $db->obtenerConexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["usuario"]);
    $email = trim($_POST["email"]);
    $contrasena = trim($_POST["contrasena"]); // SIN cifrado (no recomendado)

    // Validar si el usuario o email ya existen antes de insertar
    $sql_check = "SELECT id FROM usuarios WHERE usuario = :usuario OR email = :email";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt_check->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        die("El usuario o email ya están registrados.");
    }

    // Insertar el nuevo usuario con rol 'monitor'
    $sql = "INSERT INTO usuarios (usuario, contrasena, rol, email) VALUES (:usuario, :contrasena, 'monitor', :email)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR); // Almacena en texto plano (no recomendado)
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Monitor creado exitosamente.";
    } else {
        echo "Error al crear el usuario.";
    }
}
?>

<form action="crear_monitor.php" method="POST">
    <label for="usuario">Usuario:</label>
    <input type="text" name="usuario" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="contrasena">Contraseña:</label>
    <input type="password" name="contrasena" required>

    <button type="submit">Crear Monitor</button>
</form>
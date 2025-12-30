<?php
session_start();
header('Content-Type: application/json'); // 🔥 Devuelve respuesta en JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mensaje = trim($_POST["mensaje"]);

    if (!empty($mensaje)) {
        // Conexión a la base de datos con PDO
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=perfilglobal;charset=utf8", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $usuario = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : "anónimo";
            $fecha = date("Y-m-d"); // Fecha actual (formato SQL)

            // Insertar en la base de datos usando consulta preparada
            $sql = "INSERT INTO sugerencias (usuario, mensaje, fecha) VALUES (:usuario, :mensaje, :fecha)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':usuario' => $usuario,
                ':mensaje' => $mensaje,
                ':fecha' => $fecha
            ]);

            echo json_encode(["success" => true, "message" => "✅ Sugerencia enviada correctamente."]);
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "❌ Error al guardar: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "⚠️ El mensaje está vacío."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "❌ Acceso inválido."]);
}
?>
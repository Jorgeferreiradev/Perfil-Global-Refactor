<?php
require_once '../../conexion.php';

$db = new ConexionDB();
$con = $db->obtenerConexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $id = $_POST['id'] ?? null;
        $cedula = filter_var($_POST['cedula'], FILTER_SANITIZE_NUMBER_INT);
        $nombre_completo = filter_var($_POST['nombre_completo'], FILTER_SANITIZE_STRING);
        $id_programa = $_POST['id_programa'] ?? null;
        $id_tipo = $_POST['id_tipo'] ?? null;
        $ano = $_POST['ano'] ?? null;
        $semestre = $_POST['semestre'] ?? null;

        if (!$id || !$cedula || !$nombre_completo || !$id_programa || !$id_tipo || !$ano || !$semestre) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        // Validar cédula duplicada
        $stmt_check = $con->prepare("SELECT COUNT(*) FROM asistentes WHERE cedula = :cedula AND id != :id LIMIT 1");
        $stmt_check->execute([
            ':cedula' => $cedula,
            ':id' => $id
        ]);

        if ($stmt_check->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'La cédula ya existe para otro asistente.']);
            exit;
        }

        // Actualizar asistente
        $sql = "UPDATE asistentes SET 
                    cedula = :cedula,
                    nombre_completo = :nombre_completo,
                    id_programa = :id_programa,
                    id_tipo = :id_tipo,
                    ano = :ano,
                    semestre = :semestre
                WHERE id = :id";
        $stmt = $con->prepare($sql);
        $stmt->execute([
            ':cedula' => $cedula,
            ':nombre_completo' => $nombre_completo,
            ':id_programa' => $id_programa,
            ':id_tipo' => $id_tipo,
            ':ano' => $ano,
            ':semestre' => $semestre,
            ':id' => $id
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

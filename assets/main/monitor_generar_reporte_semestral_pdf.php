<?php
ob_start();
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'monitor') {
    header("Location: ../../login.php");
    exit();
}

require_once('../../conexion.php');
require_once('../../vendor/autoload.php');
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');

$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

$ano = $_GET['ano'] ?? '';
$semestre = $_GET['semestre'] ?? '';

if (empty($ano) || empty($semestre)) {
    die('Parámetros inválidos');
}

try {
    // Preparar y ejecutar la consulta con parámetros bind
    $sql = "
        SELECT 
            e.nombre_evento AS nombre_evento,
            e.fecha_inicio AS fecha,
            la.nombre AS linea_accion,
            e.tipo_orientacion,
            e.sede,
            e.modalidad,
            e.nivel_academico,
            COUNT(DISTINCT a.cedula) AS total_beneficiarios
        FROM eventos e
        LEFT JOIN asistencia asi ON asi.id_evento = e.id_evento
        LEFT JOIN asistentes a ON a.id = asi.id
        LEFT JOIN lineas_accion la ON la.id = e.id_linea_accion
        WHERE e.ano = :ano AND e.semestre = :semestre
        GROUP BY e.id_evento
        ORDER BY e.fecha_inicio ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ano' => $ano, 'semestre' => $semestre]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}

// Iniciar TCPDF
$pdf = new \TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Perfil Global');
$pdf->SetTitle('Reporte Semestral');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

$html = '
<h2 style="text-align:center;">Reporte Semestral</h2>
<p><strong>Año:</strong> ' . htmlspecialchars($ano) . ' &nbsp;&nbsp;&nbsp; <strong>Semestre:</strong> ' . htmlspecialchars($semestre) . '</p>
<table border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr style="background-color:#f0f0f0;">
            <th><strong>Nombre del Evento</strong></th>
            <th><strong>Fecha</strong></th>
            <th><strong>Línea de Acción</strong></th>
            <th><strong>Tipo de Orientación</strong></th>
            <th><strong>Sede</strong></th>
            <th><strong>Modalidad</strong></th>
            <th><strong>Nivel Académico</strong></th>
            <th><strong>Beneficiarios Reales</strong></th>
        </tr>
    </thead>
    <tbody>';

if ($resultados) {
    foreach ($resultados as $fila) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($fila['nombre_evento']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['fecha']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['linea_accion']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['tipo_orientacion']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['sede']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['modalidad']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['nivel_academico']) . '</td>';
        $html .= '<td style="text-align:center;">' . $fila['total_beneficiarios'] . '</td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr><td colspan="8" style="text-align:center;">No se encontraron resultados.</td></tr>';
}

$html .= '</tbody></table>';

// Escribimos el HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Limpiar buffer antes de enviar el PDF para evitar salidas previas
ob_end_clean();

// Mostrar PDF en el navegador (I = inline)
$pdf->Output('reporte_semestral_' . $ano . '_S' . $semestre . '.pdf', 'I');

exit(); // Salir para no procesar nada más después de generar PDF
?>

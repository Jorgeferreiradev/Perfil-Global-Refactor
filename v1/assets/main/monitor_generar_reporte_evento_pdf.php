<?php
ob_start();
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'monitor') {
    header("Location: ../../login.php");
    exit();
}
require_once('../../conexion.php');
require_once('../../vendor/autoload.php');
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php'); // Asegúrate de incluir TCPDF si no tienes autoload

$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

$id_evento = isset($_GET['id_evento']) && is_numeric($_GET['id_evento']) ? intval($_GET['id_evento']) : 0;
$cedula = $_GET['cedula'] ?? '';

if ($id_evento <= 0) {
    ob_end_clean(); // Limpiar cualquier salida antes de dar error
    header("Location: monitor_04_reportes_evento.php?error=evento_invalido");
    exit();
}

// Consultar evento
$consulta_evento = $pdo->prepare("
    SELECT nombre_evento, fecha_inicio, fecha_final 
    FROM eventos 
    WHERE id_evento = ?
");
$consulta_evento->execute([$id_evento]);
$evento = $consulta_evento->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    ob_end_clean();
    header("Location: monitor_04_reportes_evento.php?error=evento_no_encontrado");
    exit();
}

// Consultar asistentes
$sql = "
    SELECT a.cedula, a.nombre_completo, ta.tipo AS tipo_asistente, p.nombre AS programa
    FROM asistencia asi
    INNER JOIN asistentes a ON asi.id_asistente = a.id
    LEFT JOIN tipos_asistentes ta ON a.id_tipo = ta.id_tipo
    LEFT JOIN programas p ON a.id_programa = p.id_programa
    WHERE asi.id_evento = :id_evento
";
$params = [':id_evento' => $id_evento];

if (!empty($cedula)) {
    $sql .= " AND a.cedula = :cedula";
    $params[':cedula'] = $cedula;
}

$sql .= " ORDER BY a.nombre_completo ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$asistentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear PDF
$pdf = new \TCPDF();
$pdf->SetCreator('Perfil Global');
$pdf->SetAuthor('Monitor');
$pdf->SetTitle('Reporte de Asistencia por Evento');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

// Título
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Reporte de Asistencia por Evento', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 11);
$pdf->Ln(4);
$pdf->Write(0, 'Evento: ' . $evento['nombre_evento']);
$pdf->Ln(6);
$pdf->Write(0, 'Fecha: ' . $evento['fecha_inicio'] . ' a ' . $evento['fecha_final']);
$pdf->Ln(6);
$pdf->Write(0, 'Total Asistentes: ' . count($asistentes));
$pdf->Ln(10);

// Tabla HTML
$tbl = '
<table border="1" cellpadding="4">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th><b>Cédula</b></th>
            <th><b>Nombre Completo</b></th>
            <th><b>Tipo de Asistente</b></th>
            <th><b>Programa</b></th>
        </tr>
    </thead>
    <tbody>';

foreach ($asistentes as $asistente) {
    $tbl .= '<tr>
        <td>' . htmlspecialchars($asistente['cedula']) . '</td>
        <td>' . htmlspecialchars($asistente['nombre_completo']) . '</td>
        <td>' . htmlspecialchars($asistente['tipo_asistente']) . '</td>
        <td>' . htmlspecialchars($asistente['programa']) . '</td>
    </tr>';
}

$tbl .= '</tbody></table>';
$pdf->writeHTML($tbl, true, false, false, false, '');

// Limpiar el búfer y forzar descarga
ob_end_clean();
$nombrePDF = 'Reporte_Evento_' . preg_replace('/[^A-Za-z0-9]/', '_', $evento['nombre_evento']) . '.pdf';
$pdf->Output($nombrePDF, 'D');
exit;
?>

<?php
// Requiere conexión y librerías
require_once('../../conexion.php');
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// Activar errores visibles (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Conexión
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();
// Parámetros
$id_evento = isset($_GET['id_evento']) && is_numeric($_GET['id_evento']) ? intval($_GET['id_evento']) : 0;
$cedula = $_GET['cedula'] ?? '';
// Datos del evento
$consulta_evento = $pdo->prepare("
    SELECT nombre_evento, fecha_inicio, fecha_final 
    FROM eventos 
    WHERE id_evento = ?
");
$consulta_evento->execute([$id_evento]);
$evento = $consulta_evento->fetch(PDO::FETCH_ASSOC);
if (!$evento) {
    exit("Evento no encontrado.");
}
// Consulta de asistentes
$sql = "
    SELECT a.cedula, a.nombre_completo, 
        COALESCE(ta.tipo, 'Sin tipo') AS tipo_asistente, 
        COALESCE(p.nombre, 'Sin programa') AS programa
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
// Crear archivo Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Reporte Evento");
// Información del evento
$sheet->setCellValue('A1', 'Reporte de Asistencia por Evento');
$sheet->setCellValue('A2', 'Evento:');
$sheet->setCellValue('B2', $evento['nombre_evento']);
$sheet->setCellValue('A3', 'Fechas:');
$sheet->setCellValue('B3', $evento['fecha_inicio'] . " a " . $evento['fecha_final']);
$sheet->setCellValue('A4', 'Total Asistentes:');
$sheet->setCellValue('B4', count($asistentes));
// Encabezados
$sheet->setCellValue('A6', 'Cédula');
$sheet->setCellValue('B6', 'Nombre Completo');
$sheet->setCellValue('C6', 'Tipo de Asistente');
$sheet->setCellValue('D6', 'Programa Académico');
$styleHeader = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'DDEBF7']
    ],
    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
];
$sheet->getStyle('A6:D6')->applyFromArray($styleHeader);
// Datos
$fila = 7;
foreach ($asistentes as $asistente) {
    $sheet->setCellValue("A{$fila}", $asistente['cedula']);
    $sheet->setCellValue("B{$fila}", $asistente['nombre_completo']);
    $sheet->setCellValue("C{$fila}", $asistente['tipo_asistente']);
    $sheet->setCellValue("D{$fila}", $asistente['programa']);
    $fila++;
}
// Ajustar ancho columnas
foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
// Guardar temporalmente y servir archivo
$nombreArchivo = 'Reporte_Evento_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $evento['nombre_evento']) . '.xlsx';
$tmpFile = tempnam(sys_get_temp_dir(), 'reporte') . '.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($tmpFile);
// Enviar al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');
ob_clean(); // << limpia buffers previos
flush();     // << fuerza el envío de headers
readfile($tmpFile);
unlink($tmpFile);
exit;
<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', '¡Hola Jorge desde PhpSpreadsheet!');

$writer = new Xlsx($spreadsheet);
$writer->save("archivo_prueba.xlsx");

echo "Archivo Excel generado correctamente.";

<?php
require_once 'vendor/autoload.php';

// Crear nueva instancia de PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configuración del documento
$pdf->SetCreator('Perfil Global');
$pdf->SetAuthor('Jorge');
$pdf->SetTitle('PDF de Prueba');
$pdf->SetMargins(20, 20, 20);
$pdf->SetAutoPageBreak(TRUE, 20);

// Agrega una página
$pdf->AddPage();

// Contenido del PDF
$html = '<h1 style="color:#2E86C1;">¡Hola Jorge!</h1>
<p>Este PDF fue generado exitosamente usando <strong>TCPDF</strong> en tu proyecto <em>Perfil Global</em>.</p>';

$pdf->writeHTML($html, true, false, true, false, '');

// Salida del PDF al navegador
$pdf->Output('prueba_tcpdf.pdf', 'I');  // 'I' = mostrar en navegador, 'D' = descarga directa

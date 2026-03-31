<?php
namespace App\Controllers;

use App\Models\Reporte;
use App\Models\Evento;
use App\Services\ExcelReportService;
use App\Services\PdfReportService;

class ReporteController {

    // VISTA PRINCIPAL (El panel de botones)
    public function index() {
        $eventoModel = new Evento();
        $listaEventos = $eventoModel->all(); 

        // Calcular semestre actual para mostrarlo en la vista (Lógica Feb-Jul)
        $mes = date('n');
        $anio = date('Y');
        
        if ($mes >= 2 && $mes <= 7) {
            $semestreTxt = "$anio-I (Feb - Jul)";
        } else {
            $anioReal = ($mes == 1) ? $anio - 1 : $anio;
            $semestreTxt = "$anioReal-II (Ago - Ene)";
        }

        $title = "Centro de Reportes";
        $active = "reportes";

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/dashboard/reportes.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    // =========================================================
    // REPORTE 1: MATRIZ SEMESTRAL (EXCEL)
    // =========================================================
    public function descargarMatriz() {
        $reporteModel = new Reporte();
        $excelService = new ExcelReportService(); 

        $sedeFiltro = $_POST['sede'] ?? 'Todas';
        
        $mes = date('n');
        $anio = date('Y');
        
        // Fechas Febrero-Julio / Agosto-Enero
        if ($mes >= 2 && $mes <= 7) {
            $fi = "$anio-02-01"; 
            $ff = "$anio-07-31"; 
            $sem = "$anio-I";
        } else {
            $anioReal = ($mes == 1) ? $anio - 1 : $anio;
            $fi = "$anioReal-08-01"; 
            $ff = ($anioReal + 1) . "-01-31"; 
            $sem = "$anioReal-II";
        }

        $datosCompletos = [];
        $lineas = $reporteModel->getLineasConEventos($fi, $ff, $sedeFiltro);

        foreach ($lineas as $nombreLinea) {
            $datosCompletos[$nombreLinea] = [
                'eventos' => $reporteModel->getEventosPorLinea($nombreLinea, $fi, $ff, $sedeFiltro),
                'reales'  => $reporteModel->getBeneficiariosRealesPorLinea($nombreLinea, $fi, $ff, $sedeFiltro)
            ];
        }

        $excelService->generarMatrizSemestral($sem, $datosCompletos, $sedeFiltro);
    }

    // =========================================================
    // REPORTE 2: INDIVIDUAL POR EVENTO (EXCEL)
    // =========================================================
    public function descargarIndividual() {
        $idEvento = $_POST['id_evento'];
        
        $reporteModel = new Reporte();
        $excelService = new ExcelReportService();

        $info = $reporteModel->getInfoEvento($idEvento);
        $asistentes = $reporteModel->getDetalleAsistentes($idEvento);

        // 2. Calcular resumen rápido (Contadores exactos)
        $resumen = ['Estudiante' => 0, 'Docente' => 0, 'Administrativo' => 0, 'Egresado' => 0, 'Invitado' => 0];
        
        foreach ($asistentes as $a) {
            $tipoReal = trim($a['tipo_vinculacion']);
            
            // Si el tipo existe exactamente como está escrito arriba, suma. Si no, va a Invitado.
            if (array_key_exists($tipoReal, $resumen)) {
                $resumen[$tipoReal]++;
            } else {
                $resumen['Invitado']++;
            }
        }

        $excelService->generarIndividual($info, $resumen, $asistentes);
    }

    // =========================================================
    // REPORTE 3: MATRIZ SEMESTRAL (PDF)
    // =========================================================
    public function descargarMatrizPdf() {
        $reporteModel = new Reporte();
        $pdfService = new PdfReportService();

        $sedeFiltro = $_POST['sede'] ?? 'Todas';

        $mes = date('n'); 
        $anio = date('Y');
        
        if ($mes >= 2 && $mes <= 7) { 
            $fi = "$anio-02-01"; $ff = "$anio-07-31"; $sem = "$anio-I"; 
        } else { 
            $anioReal = ($mes == 1) ? $anio - 1 : $anio;
            $fi = "$anioReal-08-01"; $ff = ($anioReal + 1) . "-01-31"; $sem = "$anioReal-II"; 
        }

        $datosCompletos = [];
        $lineas = $reporteModel->getLineasConEventos($fi, $ff, $sedeFiltro);
        
        foreach ($lineas as $nombreLinea) {
            $datosCompletos[$nombreLinea] = [
                'eventos' => $reporteModel->getEventosPorLinea($nombreLinea, $fi, $ff, $sedeFiltro),
                'reales'  => $reporteModel->getBeneficiariosRealesPorLinea($nombreLinea, $fi, $ff, $sedeFiltro)
            ];
        }

        $pdfService->generarMatrizSemestral($sem, $datosCompletos, $sedeFiltro);
    }

    // =========================================================
    // REPORTE 4: INDIVIDUAL POR EVENTO (PDF)
    // =========================================================
    public function descargarIndividualPdf() {
        $idEvento = $_POST['id_evento'];
        
        $reporteModel = new Reporte();
        $pdfService = new PdfReportService();

        $info = $reporteModel->getInfoEvento($idEvento);
        $asistentes = $reporteModel->getDetalleAsistentes($idEvento);
        
        // 2. Calcular resumen rápido (Contadores exactos)
        $resumen = ['Estudiante' => 0, 'Docente' => 0, 'Administrativo' => 0, 'Egresado' => 0, 'Invitado' => 0];
        
        foreach ($asistentes as $a) {
            $tipoReal = trim($a['tipo_vinculacion']);
            if (array_key_exists($tipoReal, $resumen)) {
                $resumen[$tipoReal]++;
            } else {
                $resumen['Invitado']++;
            }
        }

        $pdfService->generarIndividual($info, $resumen, $asistentes);
    }
}
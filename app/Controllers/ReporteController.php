<?php
namespace App\Controllers;

use App\Models\Reporte;
use App\Models\Evento;
use App\Services\ExcelReportService;
use App\Services\PdfReportService;
use Config\Database;

class ReporteController {

    // 🔥 HELPER SENIOR: Función centralizada para obtener el periodo de la sesión
    private function obtenerContextoPeriodo() {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM periodos_academicos WHERE id = ?");
        $stmt->execute([$_SESSION['periodo_vista_id']]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // VISTA PRINCIPAL (El panel de botones)
    public function index() {
        $eventoModel = new Evento();
        $listaEventos = $eventoModel->all(); 

        // 🔥 FIX: Mostrar el nombre del periodo actual basado en la sesión
        $semestreTxt = $_SESSION['periodo_vista_nombre'] ?? 'Periodo Desconocido';

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
        
        $periodo = $this->obtenerContextoPeriodo();
        $sedeFiltro = $_POST['sede'] ?? 'Todas';
        
        // 🔥 FIX: Fechas exactas del periodo de la BD
        $fi = $periodo['fecha_inicio'];
        $ff = $periodo['fecha_fin'];
        $sem = $periodo['nombre_periodo'];

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

        $resumen = ['Estudiante' => 0, 'Docente' => 0, 'Administrativo' => 0, 'Graduado' => 0, 'Invitado' => 0];
        
        foreach ($asistentes as $a) {
            $tipoReal = trim($a['tipo_vinculacion']);
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
        
        $periodo = $this->obtenerContextoPeriodo();
        $sedeFiltro = $_POST['sede'] ?? 'Todas';

        // 🔥 FIX: Fechas exactas del periodo de la BD
        $fi = $periodo['fecha_inicio'];
        $ff = $periodo['fecha_fin'];
        $sem = $periodo['nombre_periodo'];

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
        
        $resumen = ['Estudiante' => 0, 'Docente' => 0, 'Administrativo' => 0, 'Graduado' => 0, 'Invitado' => 0];
        
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
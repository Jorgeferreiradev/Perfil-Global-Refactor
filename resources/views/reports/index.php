<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-bar me-2"></i>Centro de Reportes</h2>
</div>

<?php if(isset($_GET['error']) && $_GET['error'] == 'sin_datos'): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i> No se encontraron registros en el rango de fechas seleccionado.
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow h-100 border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-excel me-2"></i>Matriz Semestral General</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    Descarga el consolidado completo de asistencias cruzado con información académica y de eventos. Ideal para el informe de gestión.
                </p>
                <form action="/dashboard/reportes/descargar" method="POST">
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="<?= date('Y-01-01') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" value="<?= date('Y-12-31') ?>" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Generar Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-university me-2"></i>Reporte por Programa</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    Filtra la participación específica de un programa académico para auditorías de calidad.
                </p>
                <div class="alert alert-light border">
                    <small>Esta funcionalidad estará disponible en el próximo sprint.</small>
                </div>
                <button class="btn btn-outline-secondary disabled w-100">Próximamente</button>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-body">
        <h5 class="card-title text-muted">Métricas del Periodo Actual</h5>
        <div class="row text-center mt-3">
            <div class="col-md-4">
                <h2 class="display-6 fw-bold text-primary">--</h2>
                <p class="text-secondary">Asistencias Totales</p>
            </div>
            <div class="col-md-4 border-start border-end">
                <h2 class="display-6 fw-bold text-success">--</h2>
                <p class="text-secondary">Eventos Realizados</p>
            </div>
            <div class="col-md-4">
                <h2 class="display-6 fw-bold text-info">--</h2>
                <p class="text-secondary">Estudiantes Únicos</p>
            </div>
        </div>
    </div>
</div>
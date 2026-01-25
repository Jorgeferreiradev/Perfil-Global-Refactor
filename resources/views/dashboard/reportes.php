<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark"><i class="fas fa-file-csv me-2 text-success"></i>Centro de Reportes</h2>
        <p class="text-muted">Descarga de matrices y listados de asistencia.</p>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 border-top border-4 border-success">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded me-3">
                        <i class="fas fa-table fa-lg"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Matriz Semestral General</h5>
                </div>
                <p class="text-muted small">
                    Genera el consolidado de todos los eventos, cruzando asistentes vs. programas académicos. 
                    Ideal para el informe de gestión.
                </p>
                
                <form action="<?= BASE_URL ?>/reportes/descargar" method="POST">
                    <input type="hidden" name="tipo_reporte" value="matriz_general">
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Desde</label>
                            <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="<?= date('Y-01-01') ?>">
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Hasta</label>
                            <input type="date" name="fecha_fin" class="form-control form-control-sm" value="<?= date('Y-12-31') ?>">
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Descargar Matriz
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 border-top border-4 border-primary">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded me-3">
                        <i class="fas fa-list-ol fa-lg"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Listado de Asistencia Detallado</h5>
                </div>
                <p class="text-muted small">
                    Descarga la lista plana de cada persona que ha asistido, con hora exacta, documento y programa.
                    Útil para auditorías.
                </p>

                <form action="<?= BASE_URL ?>/reportes/descargar" method="POST">
                    <input type="hidden" name="tipo_reporte" value="detallado_asistentes">
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Desde</label>
                            <input type="date" name="fecha_inicio" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Hasta</label>
                            <input type="date" name="fecha_fin" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-file-export me-2"></i>Exportar Listado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-box { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
</style>
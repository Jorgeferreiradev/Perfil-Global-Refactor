<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold text-dark"><i class="fas fa-chart-pie me-2 text-primary"></i>Informes y Métricas</h2>
        <p class="text-muted">Descarga de matrices semestrales y listados de asistencia.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg h-100 bg-primary text-white">
            <div class="card-body p-4 text-center">
                <i class="fas fa-layer-group fa-3x mb-3"></i>
                <h3 class="fw-bold">Matriz General Semestral</h3>
                <p class="opacity-75 mb-4">
                    Consolidado de todas las Líneas de Acción con cálculo de <strong>Beneficiarios Reales</strong> (sin duplicados).
                </p>
                
                <form action="<?= BASE_URL ?>/dashboard/reportes/descargar-matriz" method="POST">
                    <button type="submit" class="btn btn-light fw-bold w-100 py-2 text-primary">
                        <i class="fas fa-download me-2"></i> Descargar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold text-dark mb-0">Reporte Individual por Evento</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">Descarga lista de asistencia con cédula y programa.</p>
                
                <form action="<?= BASE_URL ?>/dashboard/reportes/descargar-individual" method="POST">
                    <div class="mb-3">
                        <select name="id_evento" class="form-select" required>
                            <option value="" selected disabled>Seleccione el evento...</option>
                            <?php foreach ($listaEventos as $ev): ?>
                                <option value="<?= $ev['id_evento'] ?>">
                                    <?= $ev['fecha_inicio'] ?> - <?= $ev['nombre_evento'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-dark">
                            <i class="fas fa-file-export me-2"></i> Generar Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
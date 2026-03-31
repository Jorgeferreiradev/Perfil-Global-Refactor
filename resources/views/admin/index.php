<div class="row mt-5">
    <div class="col-12">
        <div class="card border-danger shadow-sm">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="fas fa-exclamation-triangle me-2"></i>Zona de Peligro: Gestión de Semestre
            </div>
            <div class="card-body bg-light">
                <p class="text-muted small mb-3">
                    <strong>Atención:</strong> Estas acciones alteran el periodo académico activo. El cierre de semestre pasará a todos los estudiantes actuales a estado "histórico" y preparará el sistema para una nueva carga masiva.
                </p>
                
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>/dashboard/admin/semestre/simular-cierre" 
                       class="btn btn-outline-info fw-bold text-dark">
                        <i class="fas fa-search me-2"></i>Simular Cierre
                    </a>

                    <a href="<?= BASE_URL ?>/dashboard/admin/semestre/forzar-cierre" 
                       class="btn btn-outline-danger fw-bold"
                       onclick="return confirm('⚠️ ADVERTENCIA CRÍTICA\n\n¿Estás completamente SEGURO de querer cerrar el semestre actual?');">
                        <i class="fas fa-calendar-times me-2"></i>Aplicar Cierre Real
                    </a>

                    <a href="<?= BASE_URL ?>/dashboard/admin/semestre/deshacer-cierre" 
                       class="btn btn-outline-warning fw-bold text-dark"
                       onclick="return confirm('⚠️ DESHACER\n\n¿Quieres regresar al semestre anterior y borrar el actual?');">
                        <i class="fas fa-undo-alt me-2"></i>Deshacer Último Cierre
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
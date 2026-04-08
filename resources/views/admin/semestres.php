<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-dark"><i class="fas fa-calendar-alt me-2 text-primary"></i>Gestión de Semestres</h1>
            <p class="text-muted">Administración de ciclos académicos y transiciones del sistema.</p>
        </div>
    </div>

    <?php if (isset($_GET['warning'])): ?>
        <div class="alert alert-warning alert-dismissible fade show fw-bold shadow-sm" role="alert">
            <i class="fas fa-search me-2"></i> <?= htmlspecialchars($_GET['warning']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show fw-bold shadow-sm" role="alert">
            <i class="fas fa-times-circle me-2"></i> <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show fw-bold shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_GET['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-danger shadow-sm mt-2">
        <div class="card-header bg-danger text-white fw-bold">
            <i class="fas fa-exclamation-triangle me-2"></i>Zona de Cierre Semestral
        </div>
        <div class="card-body bg-light p-4">
            <p class="text-muted mb-4">
                <strong>Atención:</strong> Estas acciones alteran el periodo académico activo de toda la plataforma. El cierre de semestre empaquetará los datos actuales como históricos y preparará el sistema en blanco para el nuevo periodo.
            </p>
            
            <div class="d-flex flex-wrap gap-3">
                <button type="button" id="btnSimular" class="btn btn-outline-info fw-bold text-dark" onclick="abrirSimulador()">
                    <i class="fas fa-search me-2"></i>1. Simular Cierre de Semestre
                </button>

                <a href="<?= BASE_URL ?>/dashboard/admin/semestres/deshacer-cierre" class="btn btn-outline-warning fw-bold text-dark" onclick="return confirm('⚠️ DESHACER\n\n¿Quieres regresar al semestre anterior y borrar el actual (si está vacío)?');">
                    <i class="fas fa-undo-alt me-2"></i> Deshacer Último Cierre
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSimulador" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-clipboard-check me-2 text-info"></i>Reporte de Impacto Semestral</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 bg-light">
                <p class="text-muted text-center mb-4">Si procedes con el cierre, este será el impacto en el sistema:</p>
                
                <div class="row g-3 text-center mb-4">
                    <div class="col-md-4">
                        <div class="p-3 bg-white rounded shadow-sm border-start border-4 border-primary">
                            <h3 class="fw-bold text-primary mb-0" id="sim_eventos">0</h3>
                            <span class="text-muted small">Eventos se archivarán</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-white rounded shadow-sm border-start border-4 border-success">
                            <h3 class="fw-bold text-success mb-0" id="sim_asistencias">0</h3>
                            <span class="text-muted small">Asistencias congeladas</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-white rounded shadow-sm border-start border-4 border-warning">
                            <h3 class="fw-bold text-warning mb-0" id="sim_estudiantes">0</h3>
                            <span class="text-muted small">Estudiantes a histórico</span>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 shadow-sm mb-3">
                    <i class="fas fa-info-circle me-2"></i> El sistema creará y activará el periodo: <strong id="sim_nuevo">...</strong>
                </div>

                <div id="alertaBloqueo" class="alert alert-danger border-0 shadow-sm d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i> <strong>¡ACCIÓN RECOMENDADA!</strong> 
                    Tienes <span id="sim_pendientes" class="fw-bold fs-5"></span> usuarios pendientes por aprobar. 
                    Se recomienda gestionar estas solicitudes antes de realizar el cierre para evitar inconsistencias.
                </div>
            </div>

            <div class="modal-footer bg-white border-top-0 d-flex justify-content-between">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                
                <a href="<?= BASE_URL ?>/dashboard/admin/semestres/forzar-cierre" id="btnForzarCierre" class="btn btn-danger fw-bold shadow-sm" onclick="return confirm('⚠️ ESTO ES IRREVERSIBLE\n\n¿Estás completamente seguro de congelar estos datos y abrir un nuevo semestre?');">
                    <i class="fas fa-lock me-2"></i>Aplicar Cierre Real
                </a>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Función: abrirSimulador
 * Utilidad: Hace una petición al controlador para traer los datos del periodo
 * actual sin recargar la página. Inyecta los datos en el Modal y evalúa si 
 * es seguro mostrar el botón de Cierre Real.
 */
function abrirSimulador() {
    const btn = document.getElementById('btnSimular');
    // Estado de carga para mejor UX
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Calculando...';
    btn.disabled = true;
    
    // Petición al endpoint del simulador
    fetch('<?= BASE_URL ?>/dashboard/admin/semestres/simular-cierre')
        .then(response => response.json())
        .then(data => {
            // Restaurar botón original
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Validar si hubo un error desde PHP
            if (!data.success) {
                alert('Error al simular: ' + data.error);
                return;
            }

            // Inyectar datos en el DOM del Modal
            document.getElementById('sim_eventos').textContent = data.eventos;
            document.getElementById('sim_asistencias').textContent = data.asistencias;
            document.getElementById('sim_estudiantes').textContent = data.estudiantes;
            document.getElementById('sim_nuevo').textContent = data.semestre_nuevo;

            const alertaBloqueo = document.getElementById('alertaBloqueo');
            const btnForzar = document.getElementById('btnForzarCierre');

            // Lógica de validación (Si hay pendientes, mostramos alerta)
            if (data.pendientes > 0) {
                document.getElementById('sim_pendientes').textContent = data.pendientes;
                alertaBloqueo.classList.remove('d-none');
                // Opcional: Podrías esconder el botón btnForzar.classList.add('d-none'); si quieres hacer el bloqueo obligatorio.
                // Por ahora lo dejamos visible pero con la advertencia en rojo.
            } else {
                alertaBloqueo.classList.add('d-none');
            }

            // Instanciar y abrir el Modal usando la API de Bootstrap 5
            let myModal = new bootstrap.Modal(document.getElementById('modalSimulador'));
            myModal.show();
        })
        .catch(error => {
            console.error('Error de conexión:', error);
            alert('Ocurrió un error al intentar contactar al servidor. Revisa la consola.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
}
</script>
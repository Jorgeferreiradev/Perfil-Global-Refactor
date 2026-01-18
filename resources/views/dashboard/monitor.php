<h2 class="mb-4">Panel de Monitor 🚀</h2>

<div class="alert alert-info shadow-sm">
    <i class="fas fa-info-circle me-2"></i> 
    Recuerda que los códigos QR generados tienen una validez limitada según la duración del evento.
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card text-center h-100 shadow-sm">
            <div class="card-body py-5">
                <div class="mb-3 text-primary">
                    <i class="fas fa-calendar-plus fa-4x"></i>
                </div>
                <h3 class="card-title">Crear Nuevo Evento</h3>
                <p class="card-text text-muted">Genera un código QR para tomar asistencia en tu actividad.</p>
                <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-primary btn-lg mt-3">Comenzar</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card text-center h-100 shadow-sm">
            <div class="card-body py-5">
                <div class="mb-3 text-success">
                    <i class="fas fa-list-alt fa-4x"></i>
                </div>
                <h3 class="card-title">Ver mis Eventos</h3>
                <p class="card-text text-muted">Consulta asistencias y descarga listas de tus eventos pasados.</p>
                <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-outline-success btn-lg mt-3">Ver Historial</a>
            </div>
        </div>
    </div>
</div>
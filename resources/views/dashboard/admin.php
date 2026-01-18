<h2 class="mb-4">Bienvenido, Administrador 👋</h2>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Total Usuarios</h6>
                        <h3 class="fw-bold text-dark mb-0">--</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Eventos Activos</h6>
                        <h3 class="fw-bold text-dark mb-0">--</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Asistencias Hoy</h6>
                        <h3 class="fw-bold text-dark mb-0">--</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="fas fa-clipboard-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-gray-800">Accesos Rápidos</h5>
    </div>
    <div class="card-body">
        <a href="<?= BASE_URL ?>/dashboard/admin/usuarios" class="btn btn-app btn-outline-dark m-2 p-3 text-center" style="min-width: 120px;">
            <i class="fas fa-user-plus fa-2x mb-2 d-block"></i> Crear Usuario
        </a>
        <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-app btn-outline-primary m-2 p-3 text-center" style="min-width: 120px;">
            <i class="fas fa-qrcode fa-2x mb-2 d-block"></i> Nuevo Evento
        </a>
        <a href="<?= BASE_URL ?>/dashboard/admin/reportes" class="btn btn-app btn-outline-success m-2 p-3 text-center" style="min-width: 120px;">
            <i class="fas fa-file-excel fa-2x mb-2 d-block"></i> Descargar Reportes
        </a>
    </div>
</div>
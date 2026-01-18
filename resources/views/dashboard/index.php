<?php $rol = $_SESSION['user_rol']; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark">Hola, <?= explode(' ', $_SESSION['user_nombre'])[0] ?> 👋</h2>
        <p class="text-muted">Bienvenido al panel de control de Bienestar Institucional.</p>
    </div>
    <span class="badge <?= $rol === 'admin' ? 'bg-danger' : 'bg-primary' ?> p-2 px-3 rounded-pill text-uppercase">
        Rol: <?= $rol ?>
    </span>
</div>

<h5 class="text-muted text-uppercase small fw-bold mb-3"><i class="fas fa-bolt me-2"></i>Gestión de Eventos</h5>
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 hover-card">
            <div class="card-body text-center p-4">
                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3">
                    <i class="fas fa-plus fa-2x"></i>
                </div>
                <h5>Nuevo Evento</h5>
                <p class="small text-muted">Generar QR para toma de asistencia.</p>
                <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-outline-primary btn-sm stretched-link">Crear Ahora</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 hover-card">
            <div class="card-body text-center p-4">
                <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3">
                    <i class="fas fa-list-ul fa-2x"></i>
                </div>
                <h5>Mis Eventos</h5>
                <p class="small text-muted">Consultar listados y asistencias.</p>
                <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-outline-success btn-sm stretched-link">Ver Lista</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-primary text-white">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <h1 class="display-4 fw-bold mb-0"><?= $data['asistencias_hoy'] ?></h1>
                <p class="mb-0 opacity-75">Asistencias registradas hoy</p>
            </div>
        </div>
    </div>
</div>

<?php if ($rol === 'admin'): ?>
    <hr class="border-secondary opacity-10 my-4">
    <h5 class="text-danger text-uppercase small fw-bold mb-3"><i class="fas fa-user-shield me-2"></i>Zona Administrativa</h5>
    
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-muted small text-uppercase">Usuarios</span>
                            <h4 class="fw-bold"><?= $data['total_usuarios'] ?></h4>
                        </div>
                        <i class="fas fa-users text-danger opacity-25 fa-2x"></i>
                    </div>
                    <a href="<?= BASE_URL ?>/dashboard/admin/usuarios" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-muted small text-uppercase">Base de Datos</span>
                            <h5 class="fw-bold text-truncate">Carga Masiva</h5>
                        </div>
                        <i class="fas fa-database text-dark opacity-25 fa-2x"></i>
                    </div>
                    <a href="<?= BASE_URL ?>/dashboard/admin/carga-masiva" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-muted small text-uppercase">Inteligencia</span>
                            <h5 class="fw-bold">Reportes</h5>
                        </div>
                        <i class="fas fa-chart-pie text-info opacity-25 fa-2x"></i>
                    </div>
                    <a href="<?= BASE_URL ?>/dashboard/admin/reportes" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    /* Estilos micro para este dashboard */
    .icon-box { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; }
    .hover-card { transition: transform 0.2s; }
    .hover-card:hover { transform: translateY(-5px); }
</style>
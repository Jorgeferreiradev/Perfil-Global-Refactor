<?php $rol = $_SESSION['user_rol'] ?? ''; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1 fs-3 fs-md-2">
            Hola, <?= explode(' ', $_SESSION['user_nombre'])[0] ?> 👋
        </h2>
        <p class="text-muted mb-0 small">Panel de Control de Perfil Global</p>
    </div>
    
    <div class="d-flex flex-wrap flex-md-column align-items-center align-items-md-end gap-2">
        <div class="badge bg-white text-dark border shadow-sm p-2 px-3 rounded-pill d-flex align-items-center">
            <i class="fas fa-calendar-alt me-2 text-primary"></i>
            <span><strong>Año <?= $data['anio_actual'] ?></strong> • Sem <?= $data['semestre_actual'] ?></span>
        </div>
        <div class="badge <?= $rol === 'admin' ? 'bg-danger' : ($rol === 'superadmin' ? 'bg-dark' : 'bg-primary') ?> p-2 px-3 rounded-pill text-uppercase shadow-sm">
            <i class="fas fa-user-shield me-1"></i> <?= $rol ?>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100 text-white overflow-hidden dashboard-card" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
            <div class="card-body p-3 p-md-4 position-relative d-flex flex-column justify-content-between">
                <div class="position-relative z-1">
                    <h6 class="text-uppercase opacity-75 fw-bold mb-1 small">Movimiento Hoy</h6>
                    <h1 class="display-5 fw-bold mb-0"><?= $data['asistencias_hoy'] ?></h1>
                </div>
                
                <div class="mt-3 mt-md-4 position-relative z-1 small opacity-75">
                    <i class="fas fa-circle fa-xs me-1 text-warning parpadeo"></i> En tiempo real
                </div>

                <i class="fas fa-chart-line position-absolute opacity-25" style="font-size: 7rem; bottom: -15px; right: -15px; transform: rotate(-5deg);"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-white dashboard-card">
            <div class="card-body p-3 p-md-4 d-flex flex-column justify-content-center">
                <div class="d-flex align-items-center mb-2 gap-2">
                    <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="badge bg-light text-dark border d-none d-sm-inline-block">BD Activa</span>
                </div>
                <h3 class="fw-bold mb-0 fs-2"><?= number_format($data['total_personas']) ?></h3>
                <p class="text-muted small mb-0 lh-sm" style="font-size: 0.8rem;">Personas registradas</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-white dashboard-card">
            <div class="card-body p-3 p-md-4 d-flex flex-column justify-content-center">
                <div class="d-flex align-items-center mb-2 gap-2">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <span class="badge bg-light text-dark border d-none d-sm-inline-block">Histórico</span>
                </div>
                <h3 class="fw-bold mb-0 fs-2"><?= number_format($data['total_eventos']) ?></h3>
                <p class="text-muted small mb-0 lh-sm" style="font-size: 0.8rem;">Eventos gestionados</p>
            </div>
        </div>
    </div>

</div>

<h6 class="text-uppercase text-muted fw-bold small mb-3 ps-1">
    <i class="fas fa-chart-pie me-2"></i>Población por Tipo
</h6>
<div class="row g-2 g-md-3 mb-5">
    <div class="col-6 col-md"> 
        <div class="card border-0 shadow-sm text-center py-3 border-bottom border-4 border-primary hover-lift">
            <div class="text-primary mb-1"><i class="fas fa-user-graduate fa-lg"></i></div>
            <h4 class="fw-bold mb-0"><?= $data['total_estudiantes'] ?></h4>
            <small class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem;">Estudiantes</small>
        </div>
    </div>
    <div class="col-6 col-md"> 
        <div class="card border-0 shadow-sm text-center py-3 border-bottom border-4 border-success hover-lift">
            <div class="text-success mb-1"><i class="fas fa-chalkboard-teacher fa-lg"></i></div>
            <h4 class="fw-bold mb-0"><?= $data['total_docentes'] ?></h4>
            <small class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem;">Docentes</small>
        </div>
    </div>
    <div class="col-6 col-md"> 
        <div class="card border-0 shadow-sm text-center py-3 border-bottom border-4 border-info hover-lift">
            <div class="text-info mb-1"><i class="fas fa-briefcase fa-lg"></i></div>
            <h4 class="fw-bold mb-0"><?= $data['total_administrativos'] ?></h4>
            <small class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem;">Admin</small>
        </div>
    </div>
    <div class="col-6 col-md"> 
        <div class="card border-0 shadow-sm text-center py-3 border-bottom border-4 border-warning hover-lift">
            <div class="text-warning mb-1"><i class="fas fa-user-tie fa-lg"></i></div>
            <h4 class="fw-bold mb-0"><?= $data['total_graduados'] ?></h4>
            <small class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem;">Graduados</small>
        </div>
    </div>
    <div class="col-12 col-md"> 
        <div class="card border-0 shadow-sm text-center py-3 border-bottom border-4 border-secondary hover-lift">
            <div class="text-secondary mb-1"><i class="fas fa-id-badge fa-lg"></i></div>
            <h4 class="fw-bold mb-0"><?= $data['total_invitados'] ?></h4>
            <small class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem;">Invitados</small>
        </div>
    </div>
</div>

<h6 class="text-uppercase text-muted fw-bold small mb-3 ps-1">
    <i class="fas fa-th-large me-2"></i>Módulos de Gestión
</h6>
<div class="row g-3 g-md-4 mb-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 hover-lift">
            <div class="card-body d-flex align-items-center p-3 p-md-4">
                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1 fs-6 fs-md-5">Directorio de Personas</h5>
                    <p class="text-muted small mb-3 d-none d-sm-block">Consulta, filtra y gestiona la base de datos completa.</p>
                    <a href="<?= BASE_URL ?>/dashboard/personas" class="btn btn-sm btn-outline-primary fw-bold">
                        Ir al Módulo <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 hover-lift">
            <div class="card-body d-flex align-items-center p-3 p-md-4">
                <div class="icon-box bg-dark bg-opacity-10 text-dark rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-qrcode fa-2x"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1 fs-6 fs-md-5">Gestión de Eventos</h5>
                    <p class="text-muted small mb-3 d-none d-sm-block">Crear eventos, generar QRs y controlar asistencias.</p>
                    <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-sm btn-outline-dark fw-bold">
                        Ir al Módulo <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($rol === 'admin' || $rol === 'superadmin'): ?>
    <?php 
        $colClass = ($rol === 'superadmin') ? 'col-12 col-md-4' : 'col-12 col-md-6'; 
    ?>
    <div class="card border-0 shadow-sm bg-light mb-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <h5 class="text-danger text-uppercase small fw-bold mb-0">
                <i class="fas fa-shield-alt me-2"></i>Zona Administrativa
            </h5>
        </div>
        <div class="card-body px-3 px-md-4 pb-4">
            <div class="row g-3">
                
                <?php if ($rol === 'superadmin'): ?>
                    <div class="<?= $colClass ?>">
                        <div class="d-flex align-items-center bg-white p-3 rounded shadow-sm border hover-lift">
                            <div class="me-3 text-secondary"><i class="fas fa-users-cog fa-2x"></i></div>
                            <div>
                                <h5 class="fw-bold mb-0"><?= $data['total_usuarios_sistema'] ?></h5>
                                <small class="text-muted" style="font-size: 0.8rem;">Usuarios Sistema</small>
                            </div>
                            <a href="<?= BASE_URL ?>/dashboard/admin/usuarios" class="ms-auto btn btn-sm btn-light"><i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="<?= $colClass ?>">
                    <div class="d-flex align-items-center bg-white p-3 rounded shadow-sm border hover-lift">
                        <div class="me-3 text-success"><i class="fas fa-file-excel fa-2x"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0"><?= $data['total_cargas'] ?></h5>
                            <small class="text-muted" style="font-size: 0.8rem;">Cargas Efectivas</small>
                        </div>
                        <a href="<?= BASE_URL ?>/dashboard/admin/carga-masiva" class="ms-auto btn btn-sm btn-light"><i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="<?= $colClass ?>">
                    <div class="d-flex align-items-center bg-white p-3 rounded shadow-sm border border-start border-4 <?= $data['total_pendientes'] > 0 ? 'border-danger' : 'border-secondary' ?> hover-lift">
                        <div class="me-3 text-danger"><i class="fas fa-user-clock fa-2x"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0"><?= $data['total_pendientes'] ?></h5>
                            <small class="text-muted" style="font-size: 0.8rem;">Pendientes</small>
                        </div>
                        <?php if($data['total_pendientes'] > 0): ?>
                            <a href="<?= BASE_URL ?>/dashboard/admin/pendientes" class="ms-auto btn btn-sm btn-danger px-3">Revisar</a>
                        <?php else: ?>
                            <span class="ms-auto text-success small"><i class="fas fa-check-circle"></i> Al día</span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    /* Estructura de tarjetas */
    .dashboard-card { border-radius: 1rem; }
    
    /* Animación Hover */
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.08)!important; }
    
    /* Animación del indicador en tiempo real */
    .parpadeo { animation: latido 1.5s infinite; }
    @keyframes latido {
        0% { opacity: 1; }
        50% { opacity: 0.3; }
        100% { opacity: 1; }
    }
</style>
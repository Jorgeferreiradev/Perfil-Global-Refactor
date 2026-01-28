<?php $rol = $_SESSION['user_rol']; ?>

<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">
            Hola, <?= explode(' ', $_SESSION['user_nombre'])[0] ?> <span class="fs-4">👋</span>
        </h2>
        <p class="text-muted mb-0">Panel de Control de Perfil Global</p>
    </div>
    <div class="text-end">
        <div class="badge bg-light text-dark border shadow-sm p-2 px-3 rounded-3 mb-2">
            <i class="fas fa-calendar-alt me-2 text-primary"></i>
            <strong>Año <?= $data['anio_actual'] ?></strong> • Semestre <?= $data['semestre_actual'] ?>
        </div>
        <div>
            <span class="badge <?= $rol === 'admin' ? 'bg-danger' : 'bg-primary' ?> p-2 px-3 rounded-pill text-uppercase">
                <i class="fas fa-user-tag me-1"></i> <?= $rol ?>
            </span>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-primary text-white position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase opacity-75 fw-bold mb-1">Movimiento Hoy</h6>
                        <h1 class="display-4 fw-bold mb-0"><?= $data['asistencias_hoy'] ?></h1>
                    </div>
                    <i class="fas fa-walking fa-3x opacity-25"></i>
                </div>
                <div class="mt-3 small opacity-75">
                    <i class="fas fa-circle fa-xs me-1 text-warning"></i> En tiempo real
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 mb-n3 me-n3 opacity-10">
                <i class="fas fa-chart-line fa-8x"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <span class="badge bg-light text-dark border">Base de Datos</span>
                </div>
                <h3 class="fw-bold mb-0"><?= number_format($data['total_personas']) ?></h3>
                <p class="text-muted small mb-0">Personas registradas (Activas)</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                    <span class="badge bg-light text-dark border">Histórico</span>
                </div>
                <h3 class="fw-bold mb-0"><?= number_format($data['total_eventos']) ?></h3>
                <p class="text-muted small mb-0">Eventos gestionados</p>
            </div>
        </div>
    </div>
</div>

<h6 class="text-uppercase text-muted fw-bold small mb-3 ps-1">
    <i class="fas fa-chart-pie me-2"></i>Población por Tipo
</h6>
<div class="row g-3 mb-5">
    <div class="col-6 col-md"> <div class="card border-0 shadow-sm text-center py-3 border-bottom border-4 border-primary">
            <div class="text-primary mb-1"><i class="fas fa-user-graduate fa-lg"></i></div>
            <h4 class="fw-bold mb-0"><?= $data['total_estudiantes'] ?></h4>
            <small class="text-muted small text-uppercase fw-bold">Estudiantes</small>
        </div>
    </div>
    <div class="col-6 col-md"> <div class="card border-0 shadow-sm text-center py-3 border-bottom border-4 border-success">
            <div class="text-success mb-1"><i class="fas fa-chalkboard-teacher fa-lg"></i></div>
            <h4 class="fw-bold mb-0"><?= $data['total_docentes'] ?></h4>
            <small class="text-muted small text-uppercase fw-bold">Docentes</small>
        </div>
    </div>
    <div class="col-6 col-md"> <div class="card border-0 shadow-sm text-center py-3 border-bottom border-4 border-info">
            <div class="text-info mb-1"><i class="fas fa-briefcase fa-lg"></i></div>
            <h4 class="fw-bold mb-0"><?= $data['total_administrativos'] ?></h4>
            <small class="text-muted small text-uppercase fw-bold">Admin.</small>
        </div>
    </div>
    <div class="col-6 col-md"> <div class="card border-0 shadow-sm text-center py-3 border-bottom border-4 border-warning">
            <div class="text-warning mb-1"><i class="fas fa-user-tie fa-lg"></i></div>
            <h4 class="fw-bold mb-0"><?= $data['total_egresados'] ?></h4>
            <small class="text-muted small text-uppercase fw-bold">Egresados</small>
        </div>
    </div>
    <div class="col-12 col-md"> <div class="card border-0 shadow-sm text-center py-3 border-bottom border-4 border-secondary">
            <div class="text-secondary mb-1"><i class="fas fa-id-badge fa-lg"></i></div>
            <h4 class="fw-bold mb-0"><?= $data['total_invitados'] ?></h4>
            <small class="text-muted small text-uppercase fw-bold">Invitados</small>
        </div>
    </div>
</div>

<h6 class="text-uppercase text-muted fw-bold small mb-3 ps-1">
    <i class="fas fa-th-large me-2"></i>Módulos de Gestión
</h6>
<div class="row g-4 mb-5">
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 hover-lift">
            <div class="card-body d-flex align-items-center p-4">
                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded me-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Directorio de Personas</h5>
                    <p class="text-muted small mb-3">Consulta, filtra y gestiona la base de datos completa (Estudiantes, Docentes, etc).</p>
                    <a href="<?= BASE_URL ?>/dashboard/personas" class="btn btn-sm btn-outline-primary fw-bold">
                        Ir al Módulo <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 hover-lift">
            <div class="card-body d-flex align-items-center p-4">
                <div class="icon-box bg-dark bg-opacity-10 text-dark rounded me-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-qrcode fa-2x"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Gestión de Eventos</h5>
                    <p class="text-muted small mb-3">Crear eventos, generar QRs y controlar asistencias.</p>
                    <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-sm btn-outline-dark fw-bold">
                        Ir al Módulo <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($rol === 'admin'): ?>
    <div class="card border-0 shadow-sm bg-light mb-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <h5 class="text-danger text-uppercase small fw-bold mb-0">
                <i class="fas fa-shield-alt me-2"></i>Zona Administrativa
            </h5>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="row g-3">
                
                <div class="col-md-4">
                    <div class="d-flex align-items-center bg-white p-3 rounded shadow-sm border">
                        <div class="me-3 text-secondary"><i class="fas fa-users-cog fa-2x"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0"><?= $data['total_usuarios_sistema'] ?></h5>
                            <small class="text-muted">Usuarios Sistema</small>
                        </div>
                        <a href="<?= BASE_URL ?>/dashboard/admin/usuarios" class="ms-auto btn btn-sm btn-light"><i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex align-items-center bg-white p-3 rounded shadow-sm border">
                        <div class="me-3 text-success"><i class="fas fa-file-excel fa-2x"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0"><?= $data['total_cargas'] ?></h5>
                            <small class="text-muted">Cargas Efectivas</small>
                        </div>
                        <a href="<?= BASE_URL ?>/dashboard/admin/carga-masiva" class="ms-auto btn btn-sm btn-light"><i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex align-items-center bg-white p-3 rounded shadow-sm border border-start border-4 <?= $data['total_pendientes'] > 0 ? 'border-danger' : 'border-secondary' ?>">
                        <div class="me-3 text-danger"><i class="fas fa-user-clock fa-2x"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0"><?= $data['total_pendientes'] ?></h5>
                            <small class="text-muted">Pendientes Aprobación</small>
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
    .icon-box { display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; }
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1)!important; }
</style>
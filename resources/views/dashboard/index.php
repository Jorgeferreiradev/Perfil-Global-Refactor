<?php $rol = $_SESSION['user_rol']; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold text-dark">
            Hola, <?= explode(' ', $_SESSION['user_nombre'])[0] ?> 
            <span class="fs-4">👋</span>
        </h2>
        <p class="text-muted mb-0">Panel de Control de Perfil Global</p>
    </div>
    <div class="text-end">
        <span class="badge <?= $rol === 'admin' ? 'bg-danger' : 'bg-primary' ?> p-2 px-3 rounded-pill text-uppercase mb-1">
            <i class="fas fa-user-tag me-1"></i> <?= $rol ?>
        </span>
        <div class="small text-muted"><?= date('d M, Y') ?></div>
    </div>
</div>

<div class="row g-4 mb-5">
    
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
                <p class="mt-3 mb-0 small opacity-75">
                    <i class="fas fa-clock me-1"></i> Actualizado en tiempo real
                </p>
            </div>
            <div class="position-absolute bottom-0 end-0 mb-n3 me-n3 opacity-10">
                <i class="fas fa-chart-area fa-8x"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 <?= ($data['total_pendientes'] > 0) ? 'border-start border-4 border-danger' : '' ?>">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-circle">
                        <i class="fas fa-user-clock fa-lg"></i>
                    </div>
                    <?php if($data['total_pendientes'] > 0): ?>
                        <span class="badge bg-danger">Requiere Atención</span>
                    <?php else: ?>
                        <span class="badge bg-success">Al día</span>
                    <?php endif; ?>
                </div>
                <h3 class="fw-bold"><?= $data['total_pendientes'] ?></h3>
                <p class="text-muted small mb-0">Solicitudes de registro esperando aprobación.</p>
                <?php if($rol === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/dashboard/admin/pendientes" class="btn btn-link text-danger p-0 mt-2 fw-bold small text-decoration-none">
                        Revisar ahora <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                <h5 class="fw-bold text-dark">Nuevo Evento</h5>
                <p class="small text-muted mb-3">Generar QR para control de acceso</p>
                <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-outline-dark rounded-pill">
                    <i class="fas fa-qrcode me-2"></i>Crear / Gestionar
                </a>
            </div>
        </div>
    </div>
</div>

<?php if ($rol === 'admin'): ?>
    <h5 class="text-muted text-uppercase small fw-bold mb-3">
        <i class="fas fa-cogs me-2"></i>Administración del Sistema
    </h5>
    
    <div class="row g-3">
        <div class="col-md-6">
            <a href="<?= BASE_URL ?>/dashboard/admin/usuarios" class="card border-0 shadow-sm text-decoration-none h-100 hover-lift">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="icon-box bg-light text-dark rounded me-3">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Gestión de Usuarios</h6>
                        <small class="text-muted">Crear monitores y administradores</small>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-muted"></i>
                </div>
            </a>
        </div>

        <div class="col-md-6">
            <a href="<?= BASE_URL ?>/dashboard/admin/carga-masiva" class="card border-0 shadow-sm text-decoration-none h-100 hover-lift">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded me-3">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Carga Masiva (Excel)</h6>
                        <small class="text-muted">Importar estudiantes y docentes</small>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-muted"></i>
                </div>
            </a>
        </div>
    </div>
<?php endif; ?>

<style>
    .icon-box { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
    .hover-lift { transition: transform 0.2s, box-shadow 0.2s; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>
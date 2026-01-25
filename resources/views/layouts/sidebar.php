<nav class="sidebar shadow-sm" id="sidebar">
    <div class="py-3 px-3 text-uppercase small fw-bold text-muted">Operación</div>
    
    <a href="<?= BASE_URL ?>/dashboard" class="<?= ($active == 'dashboard') ? 'active' : '' ?>">
        <i class="fas fa-home me-3" style="width:20px"></i> Inicio
    </a>
    
    <a href="<?= BASE_URL ?>/dashboard/eventos" class="<?= ($active == 'eventos') ? 'active' : '' ?>">
        <i class="fas fa-qrcode me-3" style="width:20px"></i> Eventos & QR
    </a>

    <?php if($_SESSION['user_rol'] === 'admin'): ?>
        <hr class="my-3 mx-3 border-secondary">
        <div class="py-2 px-3 text-uppercase small fw-bold text-danger">Administración</div>
        
        <a href="<?= BASE_URL ?>/dashboard/admin/usuarios" class="<?= ($active == 'usuarios') ? 'active' : '' ?>">
            <i class="fas fa-users-cog me-3" style="width:20px"></i> Usuarios
        </a>

        <!-- 🔥 NUEVO: APROBACIONES -->
        <a href="<?= BASE_URL ?>/dashboard/admin/pendientes" class="<?= ($active == 'pendientes') ? 'active' : '' ?>">
            <i class="fas fa-user-check me-3" style="width:20px"></i> Aprobaciones
        </a>

        <a href="<?= BASE_URL ?>/dashboard/admin/carga-masiva" class="<?= ($active == 'carga-masiva') ? 'active' : '' ?>">
            <i class="fas fa-cloud-upload-alt me-3" style="width:20px"></i> Carga Masiva
        </a>

        <a href="<?= BASE_URL ?>/dashboard/admin/reportes" class="<?= ($active == 'reportes') ? 'active' : '' ?>">
            <i class="fas fa-chart-line me-3" style="width:20px"></i> Reportes
        </a>
    <?php endif; ?>
</nav>

<main class="content d-flex flex-column">

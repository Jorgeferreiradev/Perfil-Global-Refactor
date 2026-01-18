<nav class="sidebar shadow-sm" id="sidebar">
    <div class="py-3 px-3 text-uppercase small fw-bold text-muted">Menú Principal</div>
    
    <a href="<?= BASE_URL ?>/dashboard" class="<?= ($active == 'dashboard') ? 'active' : '' ?>">
        <i class="fas fa-tachometer-alt me-3" style="width:20px"></i> Dashboard
    </a>
    
    <a href="<?= BASE_URL ?>/dashboard/eventos" class="<?= ($active == 'eventos') ? 'active' : '' ?>">
        <i class="fas fa-calendar-check me-3" style="width:20px"></i> Mis Eventos
    </a>

    <?php if($_SESSION['user_rol'] === 'admin' || $_SESSION['user_rol'] === 'dev'): ?>
        <div class="py-3 px-3 mt-3 text-uppercase small fw-bold text-muted">Administración</div>
        
        <a href="<?= BASE_URL ?>/dashboard/admin/usuarios" class="<?= ($active == 'usuarios') ? 'active' : '' ?>">
            <i class="fas fa-users-cog me-3" style="width:20px"></i> Usuarios
        </a>
        <a href="<?= BASE_URL ?>/dashboard/admin/carga-masiva" class="<?= ($active == 'carga-masiva') ? 'active' : '' ?>">
            <i class="fas fa-database me-3" style="width:20px"></i> Carga Masiva
        </a>
        <a href="<?= BASE_URL ?>/dashboard/admin/reportes" class="<?= ($active == 'reportes') ? 'active' : '' ?>">
            <i class="fas fa-file-excel me-3" style="width:20px"></i> Reportes
        </a>
    <?php endif; ?>
</nav>

<main class="content">
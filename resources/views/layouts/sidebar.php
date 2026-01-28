<style>
:root {
    --header-height: 56px;
    --sidebar-width: 260px;
}

/* Sidebar fijo debajo del header */
.sidebar {
    position: fixed;
    top: var(--header-height);
    left: 0;
    width: var(--sidebar-width);
    height: calc(100vh - var(--header-height));
    background: #212529;
    overflow-y: auto;
    z-index: 900; /* menor que el navbar */
}

/* Área de contenido */
.content {
    margin-left: var(--sidebar-width);
    padding: 20px;
    background: #f4f6f9;
    min-height: calc(100vh - var(--header-height));
}

</style>

<nav class="sidebar shadow-sm" id="sidebar">
    <div class="py-3 px-3 text-uppercase small fw-bold text-muted">Operación</div>
    
    <a href="<?= BASE_URL ?>/dashboard" class="<?= ($active == 'dashboard') ? 'active' : '' ?>">
        <i class="fas fa-home me-3" style="width:20px"></i> Inicio
    </a>
    
    <a href="<?= BASE_URL ?>/dashboard/eventos" class="<?= ($active == 'eventos') ? 'active' : '' ?>">
        <i class="fas fa-qrcode me-3" style="width:20px"></i> Eventos & QR
    </a>

    <a href="<?= BASE_URL ?>/dashboard/reportes" class="<?= ($active == 'reportes') ? 'active' : '' ?>">
        <i class="fas fa-file-csv me-3" style="width:20px"></i> Reportes
    </a>

    <a href="<?= BASE_URL ?>/dashboard/personas" class="<?= ($active == 'personas') ? 'active' : '' ?>">
        <i class="fas fa-user-friends me-3" style="width:20px"></i>  Personas
    </a>

    <?php if ($_SESSION['user_rol'] === 'admin'): ?>
        <hr class="my-3 mx-3 border-secondary opacity-25">
        <div class="py-2 px-3 text-uppercase small fw-bold text-danger">Administración</div>
        
        <a href="<?= BASE_URL ?>/dashboard/admin/usuarios" class="<?= ($active == 'usuarios') ? 'active' : '' ?>">
            <i class="fas fa-users-cog me-3" style="width:20px"></i> Usuarios
        </a>

        <a href="<?= BASE_URL ?>/dashboard/admin/pendientes" 
            class="<?= ($active == 'pendientes') ? 'active fw-bold text-primary' : '' ?>">
            <div class="d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-user-clock me-3" style="width:20px"></i> Aprobaciones
                </span>
                <?php if (!empty($_SESSION['pendientes_count'])): ?>
                    <span class="badge rounded-pill bg-danger">
                        <?= $_SESSION['pendientes_count'] ?>
                    </span>
                <?php endif; ?>
            </div>
        </a>

        <a href="<?= BASE_URL ?>/dashboard/admin/carga-masiva" class="<?= ($active == 'carga-masiva') ? 'active' : '' ?>">
            <i class="fas fa-cloud-upload-alt me-3" style="width:20px"></i> Carga Masiva
        </a>
        
    <?php endif; ?>
</nav>

<main class="content d-flex flex-column">
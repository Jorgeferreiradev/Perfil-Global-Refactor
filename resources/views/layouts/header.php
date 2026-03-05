<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'PerfilGlobal V2' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/favicon.ico">

    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 56px;
        }
        
        body { min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; background: #f4f6f9; }
        .wrapper { display: flex; flex: 1; width: 100%; }

        /* === 1. SIDEBAR GLOBAL === */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: #212529;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1040;
        }
        
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 12px 20px; display: flex; align-items: center; border-left: 3px solid transparent; }
        .sidebar a:hover { background: #343a40; color: #fff; }
        .sidebar a.active { background: #343a40; color: #0d6efd; border-left-color: #0d6efd; }

        /* === 2. CONTENIDO GLOBAL === */
        .content {
            margin-left: var(--sidebar-width);
            width: 100%;
            padding: 20px;
            transition: margin-left 0.3s ease;
            min-height: calc(100vh - var(--header-height));
        }

        /* === 3. ESTADOS DE ESCRITORIO (TOGGLE) === */
        .sidebar.toggled { transform: translateX(-100%); }
        .content.toggled { margin-left: 0; }

        /* === 4. RESPONSIVE MOBILE (Móviles y Tablets) === */
        @media (max-width: 768px) {
            /* Ocultar sidebar por defecto */
            .sidebar { transform: translateX(-100%); }
            
            /* El contenido ocupa todo el ancho */
            .content { margin-left: 0; }
            
            /* Clase que activa el JS para mostrarlo */
            .sidebar.show-mobile { transform: translateX(0); }
            
            /* Fondo oscuro detrás del menú en celular */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: var(--header-height);
                left: 0;
                width: 100vw;
                height: calc(100vh - var(--header-height));
                background: rgba(0,0,0,0.5);
                z-index: 1030;
            }
            .sidebar-overlay.show { display: block; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary sticky-top" style="height: var(--header-height);">
    <div class="container-fluid">
        <button class="btn btn-outline-light btn-sm me-3" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/dashboard">Perfil <span class="text-primary">Global</span></a>
        
        <div class="d-flex align-items-center">
            <?php if(isset($_SESSION['is_sandbox'])): ?>
                <span class="badge bg-warning text-dark me-3 animate__animated animate__pulse animate__infinite">SANDBOX</span>
            <?php endif; ?>
            
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userMenu" data-bs-toggle="dropdown">
                    <div class="bg-primary rounded-circle text-center me-2" style="width:32px; height:32px; line-height:32px;">
                        <i class="fas fa-user small"></i>
                    </div>
                    <span class="d-none d-sm-inline small"><?= $_SESSION['user_nombre'] ?? 'Usuario' ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li>
                        <h6 class="dropdown-header">
                            Conectado como:<br>
                            <strong><?= $_SESSION['user_nombre'] ?? '' ?></strong>
                        </h6>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/perfil">
                            <i class="fas fa-user-cog me-2"></i>Mi Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="wrapper">
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
        body { min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; }
        .wrapper { display: flex; flex: 1; }
        .sidebar { min-width: 260px; max-width: 260px; background: #212529; color: #fff; min-height: calc(100vh - 56px); transition: all 0.3s; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 12px 20px; display: flex; align-items: center; border-left: 3px solid transparent; }
        .sidebar a:hover { background: #343a40; color: #fff; }
        .sidebar a.active { background: #343a40; color: #0d6efd; border-left-color: #0d6efd; }
        .content { flex: 1; padding: 20px; background: #f4f6f9; width: 100%; }
        /* Responsivo */
        @media (max-width: 768px) { .sidebar { margin-left: -260px; position: absolute; z-index: 1000; height: 100%; } .sidebar.active { margin-left: 0; } }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary sticky-top">
    <div class="container-fluid">
        <button class="btn btn-outline-light btn-sm me-3 d-md-none" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <a class="navbar-brand fw-bold" href="#">Perfil <span class="text-primary">Global</span></a>
        
        <div class="d-flex align-items-center">
            <?php if(isset($_SESSION['is_sandbox'])): ?>
                <span class="badge bg-warning text-dark me-3 animate__animated animate__pulse animate__infinite">SANDBOX MODE</span>
            <?php endif; ?>
            
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userMenu" data-bs-toggle="dropdown">
                    <div class="bg-primary rounded-circle text-center me-2" style="width:32px; height:32px; line-height:32px;">
                        <i class="fas fa-user small"></i>
                    </div>
                    <span class="d-none d-sm-inline small"><?= $_SESSION['user_nombre'] ?? 'Usuario' ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-2"></i>Mi Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="wrapper">
<?php
// === 1. LÓGICA DEL MOTOR DE TIEMPO (Debe ir antes de cualquier salida HTML) ===
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
$pdoHeader = \Config\Database::getInstance();

// Si el usuario seleccionó un periodo diferente en el menú desplegable
if (isset($_GET['cambiar_periodo'])) {
    $id_nuevo = (int)$_GET['cambiar_periodo'];
    $stmt = $pdoHeader->prepare("SELECT id, nombre_periodo, estado FROM periodos_academicos WHERE id = ?");
    $stmt->execute([$id_nuevo]);
    $periodo = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    if ($periodo) {
        $_SESSION['periodo_vista_id'] = $periodo['id'];
        $_SESSION['periodo_vista_nombre'] = $periodo['nombre_periodo'];
        $_SESSION['periodo_vista_estado'] = $periodo['estado'];
    }
    // Limpiamos la URL y recargamos
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Si no hay periodo configurado en la sesión, cargamos el ACTIVO por defecto
if (!isset($_SESSION['periodo_vista_id'])) {
    $stmt = $pdoHeader->query("SELECT id, nombre_periodo, estado FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
    $activo = $stmt->fetch(\PDO::FETCH_ASSOC);
    if ($activo) {
        $_SESSION['periodo_vista_id'] = $activo['id'];
        $_SESSION['periodo_vista_nombre'] = $activo['nombre_periodo'];
        $_SESSION['periodo_vista_estado'] = $activo['estado'];
    }
}

// Consultamos todos los periodos para armar el menú desplegable
$listaPeriodos = $pdoHeader->query("SELECT id, nombre_periodo, estado FROM periodos_academicos ORDER BY id DESC")->fetchAll(\PDO::FETCH_ASSOC);

// === 2. DETECCIÓN DE SEMESTRE VENCIDO (Solo para Admins/Superadmins) ===
$alertaCierreGlobal = false;
$nombreSemestreVencido = '';

if (isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['admin', 'superadmin'])) {
    // Buscamos el periodo que REALMENTE está activo en la base de datos
    $stmtVencido = $pdoHeader->query("SELECT nombre_periodo, fecha_fin FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
    $periodoRealActivo = $stmtVencido->fetch(\PDO::FETCH_ASSOC);

    // Si la fecha de hoy superó la fecha de fin del semestre activo
    if ($periodoRealActivo && date('Y-m-d') > $periodoRealActivo['fecha_fin']) {
        $alertaCierreGlobal = true;
        $nombreSemestreVencido = $periodoRealActivo['nombre_periodo'];
    }
}

?>
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
    <div class="container-fluid px-3">
        
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-light btn-sm me-3" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand fw-bold m-0" href="<?= BASE_URL ?>/dashboard">Perfil <span class="text-primary">Global</span></a>
        </div>
        
        <div class="d-flex align-items-center gap-2 gap-md-3">
            
            <?php if(isset($_SESSION['is_sandbox'])): ?>
                <span class="badge bg-warning text-dark d-none d-sm-inline animate__animated animate__pulse animate__infinite">SANDBOX</span>
            <?php endif; ?>
            
            <div class="dropdown">
                <a class="text-decoration-none dropdown-toggle fw-bold <?= ($_SESSION['periodo_vista_estado'] == 'cerrado') ? 'text-warning bg-dark rounded px-2 py-1 border border-warning' : 'text-success bg-light rounded px-2 py-1 border' ?>" href="#" id="navbarPeriodo" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.85rem;">
                    <i class="fas fa-calendar-alt me-1"></i> 
                    <span class="d-none d-md-inline"><?= $_SESSION['periodo_vista_nombre'] ?? 'Sin Periodo' ?></span>
                    <span class="d-inline d-md-none">Semestre</span>
                    <?= ($_SESSION['periodo_vista_estado'] == 'cerrado') ? ' 🔒' : ' ✅' ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="navbarPeriodo" style="min-width: 250px; z-index: 1050;">
                    <li><h6 class="dropdown-header text-uppercase small text-muted">Viajar en el tiempo:</h6></li>
                    <?php foreach ($listaPeriodos as $p): ?>
                        <li>
                            <a class="dropdown-item py-2 border-bottom <?= ($p['id'] == $_SESSION['periodo_vista_id']) ? 'active bg-primary text-white' : 'text-dark' ?>" 
                               href="?cambiar_periodo=<?= $p['id'] ?>">
                                <?= $p['nombre_periodo'] ?> 
                                <?= ($p['estado'] == 'cerrado') ? '<span class="badge bg-secondary float-end mt-1">Histórico</span>' : '<span class="badge bg-success float-end mt-1">Activo</span>' ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-primary rounded-circle text-center me-md-2" style="width:32px; height:32px; line-height:32px;">
                        <i class="fas fa-user small"></i>
                    </div>
                    <span class="d-none d-md-inline small fw-bold"><?= $_SESSION['user_nombre'] ?? 'Usuario' ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userMenu" style="z-index: 1050;">
                    <li>
                        <h6 class="dropdown-header">
                            Conectado como:<br>
                            <strong class="text-dark"><?= $_SESSION['user_nombre'] ?? '' ?></strong>
                        </h6>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/perfil">
                            <i class="fas fa-user-cog me-2 text-secondary"></i>Mi Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger fw-bold" href="<?= BASE_URL ?>/logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>

<?php if (isset($alertaCierreGlobal) && $alertaCierreGlobal): ?>
<div class="bg-warning px-4 py-3 border-bottom border-warning shadow-sm" style="z-index: 1020; position: relative;">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3 text-dark"></i>
            <div>
                <h6 class="fw-bold mb-1 text-dark">¡Acción Requerida: El <?= htmlspecialchars($nombreSemestreVencido) ?> ya finalizó!</h6>
                <p class="mb-0 text-dark small">
                    Si ya terminaste de registrar eventos, realiza el cierre para abrir el nuevo semestre. Recuerda que esta acción restablecerá los contadores a cero y preparará el sistema para el nuevo ciclo.
                </p>
            </div>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/dashboard/admin/semestres" class="btn btn-dark btn-sm fw-bold shadow-sm">
                Ir a Gestión Semestral <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="wrapper">
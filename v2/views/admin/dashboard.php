<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PerfilGlobal V2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-width: 250px; }
        body { background-color: #f4f7f6; }
        .sidebar { 
            width: var(--sidebar-width); 
            height: 100vh; 
            position: fixed; 
            background: #2c3e50; 
            color: white;
            padding-top: 20px;
        }
        .main-content { 
            margin-left: var(--sidebar-width); 
            padding: 30px; 
        }
        .nav-link { color: #bdc3c7; transition: 0.3s; }
        .nav-link:hover { color: white; background: rgba(255,255,255,0.1); }
        .card-custom { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column p-3">
        <h3 class="text-center fw-bold border-bottom pb-3">PG <span class="text-info">V2</span></h3>
        <ul class="nav nav-pills flex-column mb-auto mt-4">
            <li class="nav-item">
                <a href="#" class="nav-link active mb-2">
                    <i class="bi bi-speedometer2 me-2"></i> Inicio
                </a>
            </li>
            <li>
                <a href="#" class="nav-link mb-2">
                    <i class="bi bi-people me-2"></i> Usuarios
                </a>
            </li>
            <li>
                <a href="#" class="nav-link mb-2">
                    <i class="bi bi-calendar-event me-2"></i> Eventos
                </a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="logout" class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-left me-2"></i> Cerrar Sesión
            </a>
        </div>
    </div>

    <div class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-secondary">Panel de Control</h2>
            <div class="user-info text-end">
                <span class="d-block fw-bold"><?= $nombre ?></span>
                <span class="badge bg-success">Administrador</span>
            </div>
        </header>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card card-custom p-4 bg-white">
                    <h6 class="text-muted">Total Usuarios</h6>
                        <h2 class="fw-bold"><?= $totalUsuarios ?? 0 ?></h2>
                    <p class="text-primary mb-0"><i class="bi bi-arrow-up"></i> Inicia carga masiva</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-custom p-4 bg-white border-start border-info border-4">
                    <h6 class="text-muted">Próximos Eventos</h6>
                    <h2 class="fw-bold">0</h2>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-custom p-4 bg-white">
                    <h6 class="text-muted">Certificados Generados</h6>
                    <h2 class="fw-bold">0</h2>
                </div>
            </div>
        </div>

        


        <div class="card card-custom p-4 bg-white mt-2">
            <h5>Bienvenido a la Versión 2.0</h5>
            <p>Desde aquí podrás gestionar la carga masiva de estudiantes y el control de asistencia para los eventos de la FESC.</p>
        </div>
        <div class="card card-custom p-4 bg-white mt-4 border-start border-primary border-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold"><i class="bi bi-file-earmark-excel me-2 text-success"></i>Carga Masiva de Estudiantes</h5>
        <a href="assets/plantilla_estudiantes.xlsx" class="btn btn-sm btn-outline-secondary">Descargar Plantilla</a>
    </div>

    #ALERTAS DE PRUEBA
                <?php if(isset($_SESSION['success'])): ?>
                <div style="color: green; background: #d4edda; padding: 10px; margin-bottom: 10px;">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                <div style="color: red; background: #f8d7da; padding: 10px; margin-bottom: 10px;">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
                <?php endif; ?>

    <form action="<?= $_ENV['APP_URL'] ?>/admin/importar-usuarios" method="POST" enctype="multipart/form-data">
            <div class="row align-items-end">
            <div class="col-md-8">
                <label class="form-label small fw-bold">Seleccionar archivo Excel (.xlsx)</label>
                <input type="file" name="archivo_excel" class="form-control" accept=".xlsx, .xls" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-cloud-arrow-up me-2"></i>Procesar e Importar
                </button>
            </div>
        </div>
    </form>
    </div>
    </div>

</body>
</html>
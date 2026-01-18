<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Asistencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e9ecef; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-asistencia { width: 100%; max-width: 350px; padding: 1.5rem; background: white; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="card-asistencia text-center">
    <img src="/assets/logo_institucional.png" alt="Logo" class="mb-3" style="max-height: 60px;">
    <h5 class="mb-1 text-primary"><?= htmlspecialchars($evento['nombre_evento']) ?></h5>
    <p class="text-muted small">Registra tu asistencia ahora</p>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">¡Registro Exitoso! ✅</div>
    <?php elseif(isset($_GET['error'])): ?>
        <div class="alert alert-danger">Documento no encontrado ❌</div>
    <?php elseif(isset($_GET['warning'])): ?>
        <div class="alert alert-warning">Ya te has registrado previamente ⚠️</div>
    <?php endif; ?>

    <form action="/asistencia/registrar" method="POST" class="mt-4">
        <input type="hidden" name="token" value="<?= $token ?>">
        <div class="mb-3 text-start">
            <label class="form-label fw-bold">Número de Documento</label>
            <input type="number" name="documento" class="form-control form-control-lg" placeholder="Ej: 1090..." required autofocus>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Registrarme</button>
        </div>
    </form>
    
    <div class="mt-4 text-muted small">
        &copy; <?= date('Y') ?> PerfilGlobal V2
    </div>
</div>

</body>
</html>
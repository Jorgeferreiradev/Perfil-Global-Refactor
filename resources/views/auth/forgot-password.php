<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | PerfilGlobal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; }
        .login-card { max-width: 400px; width: 100%; margin: auto; border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="login-card card p-4">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Recuperar <span class="text-dark">Clave</span></h2>
        <p class="text-muted">Ingresa tu correo para enviarte un enlace de recuperación.</p>
    </div>

    <form action="<?= BASE_URL ?>/auth/recovery" method="POST">
        <div class="mb-3">
            <label class="form-label">Correo Institucional</label>
            <input type="email" name="correo" class="form-control" placeholder="ejemplo@fesc.edu.co" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Enviar Enlace</button>
    </form>
    
    <div class="text-center mt-3 small">
        <a href="<?= BASE_URL ?>/login" class="text-decoration-none">Volver al Login</a>
    </div>
</div>
</body>
</html>
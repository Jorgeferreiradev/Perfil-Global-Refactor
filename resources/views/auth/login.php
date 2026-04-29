<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PerfilGlobal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= BASE_URL ?>/favicon.ico?v=1" type="image/x-icon">
    <style>
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; }
        .login-card { max-width: 400px; width: 100%; margin: auto; border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="login-card card p-4">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">PerfilGlobal <span class="text-dark">FESC</span></h2>
        <p class="text-muted">Ingresa tus credenciales</p>
    </div>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger p-2 small text-center">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

<form action="/perfilglobal_v2/public/auth/login" method="POST">        
        <div class="mb-3">
            <label class="form-label">Correo Institucional</label>
            <input type="email" name="correo" class="form-control" placeholder="ejemplo@fesc.edu.co" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Ingresar</button>
    </form>
    
    <div class="text-center mt-3 small">
        <a href="<?= BASE_URL ?>/auth/forgot-password" class="text-decoration-none">¿Olvidaste tu contraseña?</a>    </div>
    </div>

</body>
</html>
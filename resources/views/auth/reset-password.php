<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña | PerfilGlobal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; }
        .login-card { max-width: 400px; width: 100%; margin: auto; border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="login-card card p-4">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Restablecer <span class="text-dark">Clave</span></h2>
        <p class="text-muted">Crea una nueva contraseña para tu cuenta.</p>
    </div>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger p-2 small text-center">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/auth/update-password" method="POST" id="resetForm">
        
        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

        <div class="mb-3">
            <label class="form-label">Nueva Contraseña</label>
            <input type="password" name="password" id="pass1" class="form-control" placeholder="******" required minlength="6">
        </div>

        <div class="mb-3">
            <label class="form-label">Confirmar Contraseña</label>
            <input type="password" name="confirm_password" id="pass2" class="form-control" placeholder="******" required minlength="6">
            <div id="passError" class="form-text text-danger d-none">Las contraseñas no coinciden.</div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Cambiar Contraseña</button>
    </form>
    
    <div class="text-center mt-3 small">
        <a href="<?= BASE_URL ?>/login" class="text-decoration-none">Cancelar y volver al Login</a>
    </div>
</div>

<script>
    document.getElementById('resetForm').addEventListener('submit', function(e) {
        const pass1 = document.getElementById('pass1').value;
        const pass2 = document.getElementById('pass2').value;
        const errorDiv = document.getElementById('passError');

        if (pass1 !== pass2) {
            e.preventDefault(); // Detiene el envío
            errorDiv.classList.remove('d-none'); // Muestra error
        } else {
            errorDiv.classList.add('d-none');
        }
    });
</script>

</body>
</html>
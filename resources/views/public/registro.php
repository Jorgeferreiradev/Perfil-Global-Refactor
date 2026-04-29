<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Registro Asistencia' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= BASE_URL ?>/favicon.ico?v=1" type="image/x-icon">
    <style>
        body { 
            background-color: #eef2f5; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mobile-card {
            width: 100%;
            max-width: 400px;
            border-radius: 35px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background: white;
        }
        .logo-img { height: 80px; margin-bottom: 40px; }
        
        /* CLASE PARA LA MAGIA DE DESAPARECER */
        .fade-out {
            opacity: 0;
            transition: opacity 1s ease-out;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card mobile-card p-4">
            
            <div class="text-center mb-4">
                <img src="<?= BASE_URL ?>/assets/img/LOGO_FESC.png" alt="FESC" class="logo-img">
            </div>

            <div class="text-center">
                <h4 class="fw-bold text-primary mb-1">Registro de Asistencia</h4>
                <p class="text-muted small mb-4">
                    <?= htmlspecialchars($evento['nombre_evento']) ?>
                </p>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success text-center">
                    <h1 class="display-4">✅</h1>
                    <strong>¡Asistencia confirmada!</strong><br>
                    <?= htmlspecialchars($_GET['nombre']) ?><br>
                    <small class="text-muted">
                    <br>
                        Te damos la bienvenida al evento.<br>
                        ¡Te esperamos en futuras actividades!
                </div>

            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-warning text-center small auto-dismiss">
                    
                    <?php if ($_GET['error'] === 'duplicado'): ?>
                        <strong>¡Ya estás dentro!</strong><br>
                        Tu asistencia ya fue registrada previamente.

                    <?php elseif ($_GET['error'] === 'pendiente_aprobacion'): ?>
                        <strong>⚠️ Cuenta Pendiente</strong><br>
                        Tu usuario existe pero requiere aprobación del Admin.
                    
                    <?php elseif ($_GET['error'] === 'solicitud_enviada'): ?>
                        <strong>📩 Solicitud Enviada</strong><br>
                        Tus datos fueron recibidos para validación.

                    <?php elseif ($_GET['error'] === 'longitud_invalida'): ?>
                        <strong>⚠️ Documento No Válido</strong><br>
                        Ingresa entre 6 y 15 dígitos reales.
                    
                    <?php else: ?>
                        <strong>Error:</strong> <?= htmlspecialchars($_GET['error']) ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($_GET['success'])): ?>
                <form action="<?= BASE_URL ?>/asistencia/registrar" method="POST">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                    <div class="mb-3 text-center">
                        <label class="form-label fw-bold">Número de Documento</label>
                        <input 
                            type="text" 
                            inputmode="numeric" 
                            pattern="[0-9]*"
                            name="documento" 
                            class="form-control form-control-lg text-center" 
                            placeholder="Mínimo 6 dígitos" 
                            minlength="6" 
                            maxlength="15"
                            required 
                            autofocus
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        >
                        <div class="form-text small text-muted text-center">
                            Ingresa tu documento sin puntos ni espacios
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                            Confirmar Asistencia
                        </button>
                    </div>
                </form>
            <?php endif; ?>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertas = document.querySelectorAll('.auto-dismiss');
            
            // 1. LÓGICA PARA ÉXITO (10 segundos y redirección)
            <?php if (isset($_GET['success'])): ?>
                let countdown = 10;
                
                // Opcional: Si quieres mostrar un contador visual en consola o en el alert
                console.log("Redirigiendo en 10 segundos...");
                
                setTimeout(() => {
                    // Redirige a la misma URL pero cortando todo lo que esté después del '?'
                    // Esto limpia el ?success=... y devuelve el formulario limpio con el token
                    window.location.href = window.location.href.split('?')[0];
                }, 10000); // 10000 milisegundos = 10 segundos exactos

            // 2. LÓGICA PARA ERRORES (4 segundos y desvanecer, sin recargar)
            <?php else: ?>
                alertas.forEach(function(alerta) {
                    setTimeout(() => {
                        alerta.classList.add('fade-out'); 
                        
                        setTimeout(() => { 
                            alerta.remove(); 
                        }, 1000); 
                        
                    }, 4000); // 4 segundos para leer el error
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <!-- Viewport separado correctamente -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos de la vista -->
    <style>
        body {
            background-color: #eef2f5;
        }

        .mobile-card {
            max-width: 400px;
            margin: 20px auto;
            border-radius: 35px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .logo-img {
            height: 50px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Card principal -->
        <div class="card mobile-card p-4">

            <!-- Logo -->
            <div class="text-center mb-4">
                <img
                    src="<?= BASE_URL ?>/assets/img/LOGO_FESC.png"
                    alt="LOGO_FESC"
                    class="logo-img"
                >
            </div>

            <!-- Encabezado -->
            <div class="text-center">
                <h4 class="fw-bold text-primary mb-1">
                    Registro de Asistencia
                </h4>
                <p class="text-muted small mb-4">
                    <?= htmlspecialchars($evento['nombre_evento']) ?>
                </p>
            </div>

            <!-- Mensajes -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success text-center">
                    <h1 class="display-4">✅</h1>
                    <strong>¡Registro Exitoso!</strong><br>
                    Bienvenido, <?= htmlspecialchars($_GET['nombre']) ?>.
                </div>

            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center">
                    <?php if ($_GET['error'] === 'no_encontrado'): ?>
                        <strong>Documento no encontrado.</strong><br>
                        No apareces en la base de datos maestra. Acércate al monitor.
                    <?php elseif ($_GET['error'] === 'duplicado'): ?>
                        <strong>Ya estás registrado.</strong><br>
                        Tu asistencia ya fue tomada previamente.
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <?php if (!isset($_GET['success'])): ?>
                <form action="<?= BASE_URL ?>/asistencia/registrar" method="POST">

                    <!-- Token -->
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Número de Documento
                        </label>
                        <input
                            type="number"
                            name="documento"
                            class="form-control form-control-lg text-center"
                            placeholder="Ej: 1090..."
                            required
                            autofocus
                        >
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Confirmar Asistencia
                        </button>
                    </div>

                </form>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>

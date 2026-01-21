<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Error de Asistencia</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos propios -->
    <style>
        body {
            background-color: #eef2f5;
            height: 100vh;
        }

        .mobile-card {
            max-width: 350px;
            border-radius: 20px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .logo-img {
            height: 50px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

    <!-- Card principal -->
    <div class="card mobile-card p-4 text-center">

        <!-- Logo -->
        <div class="mb-4">
            <img
                src="<?= BASE_URL ?>/assets/img/LOGO_FESC.png"
                alt="LOGO_FESC"
                class="logo-img"
            >
        </div>

        <!-- Ícono de error -->
        <div class="text-danger mb-3">
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="64"
                 height="64"
                 fill="currentColor"
                 class="bi bi-x-circle"
                 viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg>
        </div>

        <!-- Texto -->
        <h4 class="fw-bold">
            <?= htmlspecialchars($titulo) ?>
        </h4>

        <p class="text-muted mb-0">
            <?= htmlspecialchars($mensaje) ?>
        </p>

    </div>

</body>
</html>

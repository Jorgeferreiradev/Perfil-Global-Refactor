<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <!-- Título de la página -->
    <title>Código QR de Asistencia</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ==============================
         ESTILOS DE LA VISTA
         ============================== -->
    <style>
        /* Ocultar elementos al imprimir */
        @media print {
            .no-print {
                display: none;
            }
        }

        /* Fondo general */
        body {
            background: #f4f7f6;
        }

        /* Card principal */
        .qr-card {
            max-width: 600px;
            margin: 50px auto;
            border-radius: 35px;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        /* Logo institucional */
        .logo-img {
            max-height: 70px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <!-- ==============================
         CONTENEDOR PRINCIPAL
         ============================== -->
    <div class="qr-card p-2 text-center">

        <!-- LOGO (independiente, NO dentro de otro card) -->
        <div class="mb-2">
            <img
                src="<?= BASE_URL ?>/assets/img/LOGO_FESC.png"
                alt="Logo FESC"
                class="logo-img"
            >
        </div>

        <!-- TÍTULO DEL EVENTO -->
        <h2 class="fw-bold mb-3">
            <?= htmlspecialchars($evento['nombre_evento']) ?>
        </h2>

        <!-- TEXTO INFORMATIVO -->
        <p class="text-muted mb-1">
            Escanea el código para registrar tu asistencia
        </p>

        <!-- CÓDIGO QR -->
        <div class="mb-2">
            <img
                src="<?= $qrImage ?>"
                alt="Código QR del evento"
                style="width: 100%; max-width: 400px; height: auto;"
            >
        </div>

        <!-- INFORMACIÓN TÉCNICA (PERIODO / TOKEN) -->
        <div class="alert alert-light border small">
            <strong>ID de Periodo:</strong> <?= $evento['id_periodo'] ?>
            <br>
            <strong>Token:</strong> <?= $token ?>
        </div>

        <!-- BOTÓN IMPRIMIR (no aparece al imprimir) -->
        <button
            onclick="window.print()"
            class="btn btn-primary no-print"
        >
            Imprimir para Auditorio
        </button>

    </div>

</body>
</html>

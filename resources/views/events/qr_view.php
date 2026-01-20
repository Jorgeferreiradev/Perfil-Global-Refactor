<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Código QR de Asistencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none; } }
        body { background: #f4f7f6; }
        .qr-card { max-width: 500px; margin: 50px auto; border-radius: 15px; background: white; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        svg { width: 100%; height: auto; }
    </style>
</head>
<body>
    <div class="qr-card p-5 text-center">
        <h2 class="fw-bold mb-3"><?= htmlspecialchars($evento['nombre_evento']) ?></h2>
        <p class="text-muted mb-4">Escanea el código para registrar tu asistencia</p>
        
        <div class="mb-4">
            <img src="<?= $qrImage ?>" alt="Código QR" style="width: 100%; max-width: 400px; height: auto;">
        </div>

        <div class="alert alert-light border small">
            ID de Periodo: <?= $evento['id_periodo'] ?> | Token: <?= $token ?>
        </div>

        <button onclick="window.print()" class="btn btn-primary no-print">
            <i class="fas fa-print"></i> Imprimir para Auditorio
        </button>
    </div>
</body>
</html>
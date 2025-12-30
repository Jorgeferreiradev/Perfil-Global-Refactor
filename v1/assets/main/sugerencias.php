<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buzón de Sugerencias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

</head>

<body class="bg-light">

<?php include_once("../../componentes/header.php");
HeaderPersonalizado::mostrar(); ?>

<main class="container my-5">
    <div class="card shadow-sm p-4">
        <h2 class="text-primary text-center">📬 Buzón de Mejoras a la Plataforma</h2>
        <p class="text-center text-muted">Tu opinión nos ayuda a mejorar. Déjanos tu sugerencia para atenderla.</p>

        <form id="formSugerencia">
            <div class="mb-3">
                <label for="mensaje" class="form-label">Escribe aqui sugerencia:</label>
                <textarea name="mensaje" id="mensaje" class="form-control" rows="5" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">📩 Enviar sugerencia</button>
            </div>
        </form>
    </div>
    
</main>

<!-- Toast de notificación -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="toast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">Sugerencia enviada correctamente.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("formSugerencia").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("procesar_sugerencias.php", {
        method: "POST",
        body: formData
    })
    .then(resp => resp.json())
    .then(data => {
        mostrarToast(data.success ? "✅ Sugerencia enviada correctamente." : "❌ Error al enviar la sugerencia.");
        if (data.success) this.reset();
    })
    .catch(() => {
        mostrarToast("❌ Error de conexión al enviar la sugerencia.", "error");
    });
});

function mostrarToast(mensaje) {
    const toastEl = document.getElementById("toast");
    document.getElementById("toastMessage").innerText = mensaje;

    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}
</script>

</body>
<?php include_once("../../componentes/footer.php");
Footer::mostrar();?>
</html>
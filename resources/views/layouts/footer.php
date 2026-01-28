<footer class="mt-auto pt-3 border-top text-center text-muted small">
    <p>
    &copy; <?= date('Y') ?> | Fundación de Estudios Superiores Comfanorte · FESC |  
    PerfilGlobal · Plataforma Institucional de Gestión Académica.
    </p>
    </footer>
<main>
</main> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');

    if (toggle && sidebar && content) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('closed');
            content.classList.toggle('full');
        });
    }
});
</script>

</body>
</html>
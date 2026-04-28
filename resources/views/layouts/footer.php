<footer class="mt-auto pt-3 border-top text-center text-muted small">

<p>
    &copy; <?= date('Y') ?> · Plataforma de Eventos Bienestar Institucional · FESC   <br>
    PerfilGlobal · Desarrollado por J. Ferreira 
</p>


    <p>
    
    </p>





    </footer>
<main>
</main> </div> 
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const overlay = document.getElementById('sidebarOverlay');

        // Función cuando se hace click en el botón hamburguesa
        toggleBtn.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                // Modo Celular: Muestra/Oculta superpuesto
                sidebar.classList.toggle('show-mobile');
                overlay.classList.toggle('show');
            } else {
                // Modo PC: Empuja y contrae la pantalla
                sidebar.classList.toggle('toggled');
                content.classList.toggle('toggled');
            }
        });

        // Si hacen click en lo oscuro del overlay en móvil, cerrar el menú
        if(overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show-mobile');
                overlay.classList.remove('show');
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
<?php

class Footer {
    /**
     * Imprime el pie de página y cierra body/html.
     */
    public static function mostrar() {
        echo '
        <footer class="bg-light text-center py-3 mt-4 border-top fixed-footer">
            <div class="container">
                <small>&copy; ' . date('Y') . ' Perfil Global. Todos los derechos reservados | Desarrollado por J.Ferreira & C.Celis.</small>
            </div> 
        </footer>
        ';

        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
        echo '</body></html>';
    }
}

?>

<!-- Agrega este CSS en tu archivo de estilos -->
<style>
    html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
}

main {
    flex-grow: 1;
}

.fixed-footer {
    width: 100%;
    background: #f8f9fa;
    text-align: center;
    padding: 15px;
    border-top: 1px solid #ccc;

}
</style>


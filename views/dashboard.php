<?php 
// Usamos rutas absolutas para no fallar
require_once __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/layouts/sidebar.php';
?>

<main class="flex-1 ml-64 p-8 bg-gray-50 min-h-screen">
    
    <header class="flex justify-between items-center mb-10">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 text-indigo-600">Hola, <?= $nombre ?></h2>
            <p class="text-sm text-slate-500 uppercase">Bienvenido al panel de <?= $rol ?></p>
        </div>
    </header>

    <div class="grid grid-cols-1 gap-6">
        <?php 
            // Verificamos si el archivo existe antes de pedirlo para evitar el Fatal Error
            $contentFile = __DIR__ . "/{$rol}/dashboard_content.php";
            
            if (file_exists($contentFile)) {
                require_once $contentFile;
            } else {
                echo "<div class='bg-red-100 p-4 text-red-700 rounded-lg'>
                        Error: Crea el archivo en <b>views/{$rol}/dashboard_content.php</b>
                      </div>";
            }
        ?>
    </div>

</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
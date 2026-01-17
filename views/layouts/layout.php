<?php 
// Este es el archivo "Pegamento"
include_once __DIR__ . '/header.php'; 
include_once __DIR__ . '/sidebar.php'; 
?>

<main class="flex-1 ml-64 p-8 bg-slate-50 min-h-screen">
    
    <header class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Hola, <?= $nombre ?> 👋</h2>
            <p class="text-sm text-slate-500 capitalize">Panel de control para <?= $rol ?></p>
        </div>
        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
            <span class="text-xs font-bold text-slate-400 uppercase">Estado:</span>
            <span class="text-sm font-bold text-green-600">En Línea</span>
        </div>
    </header>

    <div class="animate-fadeIn">
        <?php 
            if (file_exists($viewContent)) {
                require_once $viewContent; 
            } else {
                echo "<div class='bg-red-100 p-4 rounded-lg text-red-700'>
                        Error: No existe el archivo de contenido en: $viewContent
                      </div>";
            }
        ?>
    </div>

</main>

<?php include_once __DIR__ . '/footer.php'; ?>
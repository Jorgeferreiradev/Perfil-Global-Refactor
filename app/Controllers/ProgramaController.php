<?php
namespace App\Controllers;

use App\Models\Programa;

class ProgramaController {
    
    public function index() {
        $model = new Programa();
        $programas = $model->getAll();
        require_once __DIR__ . '/../../resources/views/programas/index.php';
    }

    public function create() {
        require_once __DIR__ . '/../../resources/views/programas/create.php';
    }

    public function store() {
        if (!empty($_POST['nombre_programa'])) {
            (new Programa())->create($_POST['nombre_programa']);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Programa creado correctamente.'];
        }
        header('Location: ' . BASE_URL . '/dashboard/admin/programas');
    }

    public function edit($id) {
        $programa = (new Programa())->getById($id);
        if (!$programa) {
            header('Location: ' . BASE_URL . '/dashboard/admin/programas');
            exit;
        }
        require_once __DIR__ . '/../../resources/views/programas/edit.php';
    }

    public function update($id) {
        if (!empty($_POST['nombre_programa'])) {
            (new Programa())->update($id, $_POST['nombre_programa']);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Programa actualizado.'];
        }
        header('Location: ' . BASE_URL . '/dashboard/admin/programas');
    }

    public function toggle($id) {
        (new Programa())->toggle($id);
        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Estado del programa modificado.'];
        header('Location: ' . BASE_URL . '/dashboard/admin/programas');
    }
}
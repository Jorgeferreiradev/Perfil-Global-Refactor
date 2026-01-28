<?php
namespace App\Controllers;

use App\Models\Persona;

class PersonasController {

    /* ===============================
       LISTADO + BÚSQUEDA
    =============================== */
    public function index() {
        $model = new Persona();

        $query = $_GET['q'] ?? '';

        if ($query) {
            $personas = $model->search($query);
        } else {
            $personas = $model->getAll();
        }

        require_once __DIR__ . '/../../resources/views/personas/index.php';
    }

    /* ===============================
       FORMULARIO CREAR
    =============================== */
    public function create() {
        $model = new Persona();
        $tipos = $model->getTipos(); // select tipos persona

        require_once __DIR__ . '/../../resources/views/personas/create.php';
    }

    /* ===============================
       GUARDAR
    =============================== */
    public function store() {
        $model = new Persona();

        // Validar duplicados
        if ($model->existeDocumento($_POST['numero_documento'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'msg'  => '¡Ya existe una persona con ese número de documento!'
            ];
            header('Location: ' . BASE_URL . '/dashboard/personas/crear');
            exit;
        }

        if ($model->create($_POST)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'msg'  => 'Persona registrada correctamente.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'msg'  => 'Error al guardar en base de datos.'
            ];
        }

        header('Location: ' . BASE_URL . '/dashboard/personas');
    }

    /* ===============================
       FORMULARIO EDITAR
    =============================== */
    public function edit($id) {
        $model = new Persona();

        $persona = $model->getById($id);
        $tipos   = $model->getTipos();

        if (!$persona) {
            header('Location: ' . BASE_URL . '/dashboard/personas');
            exit;
        }

        require_once __DIR__ . '/../../resources/views/personas/edit.php';
    }

    /* ===============================
       ACTUALIZAR
    =============================== */
    public function update($id) {
        $model = new Persona();

        // Validar duplicado excluyendo el actual
        if ($model->existeDocumento($_POST['numero_documento'], $id)) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'msg'  => '¡El documento ya pertenece a otra persona!'
            ];
            header('Location: ' . BASE_URL . '/dashboard/personas/editar/' . $id);
            exit;
        }

        if ($model->update($id, $_POST)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'msg'  => 'Datos actualizados correctamente.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'msg'  => 'Error al actualizar.'
            ];
        }

        header('Location: ' . BASE_URL . '/dashboard/personas');
    }

    /* ===============================
       ELIMINAR (SOFT DELETE)
    =============================== */
    public function delete($id) {
        $model = new Persona();
        $model->delete($id);

        $_SESSION['flash'] = [
            'type' => 'warning',
            'msg'  => 'Persona eliminada del sistema.'
        ];

        header('Location: ' . BASE_URL . '/dashboard/personas');
    }
}

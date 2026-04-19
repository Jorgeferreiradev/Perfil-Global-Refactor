<?php
namespace App\Controllers;

use App\Models\Persona;
use App\Models\Periodo;
use Config\Database;

class PersonasController {

    /**
     * 🔥 HELPER SENIOR: Bloquea intentos de escritura en semestres históricos
     */
    private function protegerSemestreHistorico() {
        if (!isset($_SESSION['periodo_vista_estado']) || $_SESSION['periodo_vista_estado'] !== 'activo') {
            $_SESSION['flash'] = [
                'type' => 'danger', 
                'msg'  => '⛔ Acción denegada: El semestre seleccionado es histórico (Solo Lectura).'
            ];
            header('Location: ' . BASE_URL . '/dashboard/personas');
            exit;
        }
    }

    /* ===============================
        LISTADO + BÚSQUEDA
    =============================== */
    public function index() {
        $model = new Persona();
        $query = $_GET['q'] ?? '';

        $personas = $query ? $model->search($query) : $model->getAll();

        require_once __DIR__ . '/../../resources/views/personas/index.php';
    }

    /* ===============================
        FORMULARIO CREAR
    =============================== */
    public function create() {
        $this->protegerSemestreHistorico();

        $model = new Persona();
        $tipos = $model->getTipos(); 
        // 🔥 CARGAMOS CARRERAS PARA EL SELECT
        $programas = $model->getProgramas(); 

        require_once __DIR__ . '/../../resources/views/personas/create.php';
    }

    /* ===============================
        GUARDAR (CON HISTORIAL)
    =============================== */
    public function store() {
        $this->protegerSemestreHistorico();

        $model = new Persona();

        if ($model->existeDocumento($_POST['numero_documento'])) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => '¡Ya existe esa identificación!'];
            header('Location: ' . BASE_URL . '/dashboard/personas/crear');
            exit;
        }

        // 1. Crear persona básica
        $idPersona = $model->create($_POST);

        if ($idPersona) {
            // 2. 🔥 LÓGICA SENIOR: Crear entrada inicial en Historial Académico
            if (!empty($_POST['id_programa'])) {
                $pdo = Database::getInstance();
                $idPeriodo = (new Periodo())->getActivoId();
                
                $sql = "INSERT INTO historial_academico (persona_id, periodo_id, id_programa, id_tipo_persona) 
                        VALUES (:pid, :per, :prog, :tipo)";
                
                $pdo->prepare($sql)->execute([
                    ':pid'  => $idPersona,
                    ':per'  => $idPeriodo,
                    ':prog' => $_POST['id_programa'],
                    ':tipo' => $_POST['id_tipo_persona'] ?? 1
                ]);
            }

            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Persona y carrera registradas.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Error en el registro.'];
        }

        header('Location: ' . BASE_URL . '/dashboard/personas');
    }

    /* ===============================
        FORMULARIO EDITAR
    =============================== */
    public function edit($id) {
        $this->protegerSemestreHistorico();

        $model = new Persona();
        $persona = $model->getById($id);
        $tipos   = $model->getTipos();
        // 🔥 CARGAMOS CARRERAS PARA EL SELECT
        $programas = $model->getProgramas(); 

        if (!$persona) {
            header('Location: ' . BASE_URL . '/dashboard/personas');
            exit;
        }

        require_once __DIR__ . '/../../resources/views/personas/edit.php';
    }

    /* ===============================
        ACTUALIZAR (BÁSICO + HISTORIAL)
    =============================== */
    public function update($id) {
        $this->protegerSemestreHistorico();

        $model = new Persona();

        if ($model->existeDocumento($_POST['numero_documento'], $id)) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'El documento ya está en uso.'];
            header('Location: ' . BASE_URL . '/dashboard/personas/editar/' . $id);
            exit;
        }

        // 1. Actualizar tabla 'personas'
        if ($model->update($id, $_POST)) {
            
            // 2. Actualizar 'historial_academico' del semestre activo
            if (!empty($_POST['id_programa'])) {
                $pdo = Database::getInstance();
                $idPeriodo = (new Periodo())->getActivoId();
                
                $sql = "UPDATE historial_academico 
                        SET id_programa = :prog, id_tipo_persona = :tipo 
                        WHERE persona_id = :pid AND periodo_id = :per";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':prog' => $_POST['id_programa'],
                    ':tipo' => $_POST['id_tipo_persona'] ?? 1,
                    ':pid'  => $id,
                    ':per'  => $idPeriodo
                ]);

                // Si no se actualizó ninguna fila (porque el registro no existía), lo insertamos
                if ($stmt->rowCount() == 0) {
                    $sqlIns = "INSERT INTO historial_academico (persona_id, periodo_id, id_programa, id_tipo_persona) 
                               VALUES (:pid, :per, :prog, :tipo)";
                    $pdo->prepare($sqlIns)->execute([
                        ':pid'  => $id,
                        ':per'  => $idPeriodo,
                        ':prog' => $_POST['id_programa'],
                        ':tipo' => $_POST['id_tipo_persona'] ?? 1
                    ]);
                }
            }

            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Datos actualizados correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Error al actualizar.'];
        }

        header('Location: ' . BASE_URL . '/dashboard/personas');
    }

    /* ===============================
        ELIMINAR
    =============================== */
    public function delete($id) {
        $this->protegerSemestreHistorico();

        $model = new Persona();
        $model->delete($id);

        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Persona eliminada.'];
        header('Location: ' . BASE_URL . '/dashboard/personas');
    }
}
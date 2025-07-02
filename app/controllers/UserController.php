<?php
namespace App\Controllers;

use App\Models\User;
use PDO;
use PDOException;

class UserController extends BaseController {
    private $userModel;

    public function __construct(PDO $conn) {
        parent::__construct($conn);
        $this->userModel = new User($conn);
    }

    public function createUser() {
        $this->checkAuth();
        $this->checkRole('admin');

        $error = '';
        $exito = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken($_POST['csrf_token'] ?? '');

            $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
            $contrasena = $_POST['contrasena'];
            $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_STRING);

            if (empty($usuario) || empty($contrasena) || empty($rol)) {
                $error = "Todos los campos son obligatorios.";
            } elseif (!in_array($rol, ['admin', 'empleado'])) {
                $error = "Tipo de empleado inválido. Los tipos permitidos son 'Administrador' o 'Empleado'.";
            } else {
                try {
                    if ($this->userModel->getUserByUsername($usuario)) {
                        $error = "El nombre de empleado ya existe.";
                    } else {
                        $this->userModel->addUser($usuario, $contrasena, $rol);
                        $exito = "Empleado '$usuario' registrado correctamente como '$rol'.";
                        $_POST = [];
                    }
                } catch (PDOException $e) {
                    error_log("Error al registrar empleado: " . $e->getMessage());
                    $error = "Error al registrar el empleado. Por favor, inténtelo de nuevo.";
                }
            }
        }

        $title = "Registrar Nuevo Empleado";
        $this->render('users/create', [
            'title' => $title,
            'error' => $error,
            'exito' => $exito,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }
}

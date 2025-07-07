<?php
namespace App\Controllers;

use App\Models\User;
use PDO;

class AuthController extends BaseController {
    private $userModel;

    public function __construct(PDO $conn) {
        parent::__construct($conn); // Llamar al constructor de la clase base
        $this->userModel = new User($conn);
    }

   public function login() {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $this->verifyCsrfToken($_POST['csrf_token'] ?? '');
        $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
        $contrasena = $_POST['contrasena'];
        $user = $this->userModel->authenticate($usuario, $contrasena);
        if ($user) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['rol'] = $user['rol'];
            $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->redirect('panelprincipal');
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    }
        // Incluir la vista de login
        $this->render_login('auth/login', ['error' => $error, 'csrf_token' => $this->generateCsrfToken()]);
    }

    public function logout() {
        session_unset();
        session_destroy();
        // Regenerar ID de sesión para evitar fijación de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        $this->redirect('index_Principal.html');
    }
}

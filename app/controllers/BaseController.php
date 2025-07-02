<?php

namespace App\Controllers; // Añadir namespace

use PDO;

class BaseController {
    protected $conn;
    protected $flashMessages = [];

    public function __construct(PDO $conn) {
        $this->conn = $conn;
        $this->loadFlashMessages();
    }

    protected function loadFlashMessages() {
        if (isset($_SESSION['flash_messages'])) {
            $this->flashMessages = $_SESSION['flash_messages'];
            unset($_SESSION['flash_messages']); // Limpiar después de cargar
        }
    }

    protected function checkAuth() {
        if (!isset($_SESSION['usuario'])) {
            $this->redirect('login');
        }
        // Seguridad de sesión: verificar user agent
        if (isset($_SESSION['HTTP_USER_AGENT']) && $_SESSION['HTTP_USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT']) {
            session_unset();
            session_destroy();
            $this->redirect('login', ['error' => 'Sesión inválida. Por favor, inicie sesión de nuevo.']);
        }
    }
    // Nuevo método para verificar el rol del usuario
    protected function checkRole($requiredRole) {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== $requiredRole) {
            $this->redirect('panelprincipal', ['error' => 'Acceso denegado. No tiene permisos para realizar esta acción.']);
        }
    }

    protected function render($viewPath, $data = []) {
        // Pasar mensajes flash a la vista
        $data['flashMessages'] = $this->flashMessages;
        extract($data); // Extraer datos para que estén disponibles en la vista

        // Asegurarse de que $title esté definido para evitar errores
        $title = $title ?? 'Sistema de Gestión';

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $viewPath . '.php';
    }

     protected function render_login($viewPath, $data = []) {
        // Pasar mensajes flash a la vista
        $data['flashMessages'] = $this->flashMessages;
        extract($data); // Extraer datos para que estén disponibles en la vista

        // Asegurarse de que $title esté definido para evitar errores
        $title = $title ?? 'Sistema de Gestión';

        require_once __DIR__ . '/../views/' . $viewPath . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    protected function redirect($path, $messages = []) {
        if (!empty($messages)) {
            $_SESSION['flash_messages'] = $messages;
        }
        header('Location: ' . $path);
        exit();
    }

    protected function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrfToken($token) {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            // Token CSRF inválido, posible ataque
            session_unset();
            session_destroy();
            $this->redirect('login', ['error' => 'Error de seguridad: Token CSRF inválido.']);
        }
        // Regenerar el token después de cada uso para mayor seguridad
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
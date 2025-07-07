<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();

try {
    $db = new App\Models\Database();
    $conn = $db->getConnection();
} catch (\RuntimeException $e) { // Capturar la excepción más genérica si se lanza desde Database
    error_log("Error fatal de conexión a la base de datos: " . $e->getMessage());
    http_response_code(500); // Enviar código de estado HTTP 500
    echo "<h1>Error interno del servidor</h1><p>No se pudo conectar a la base de datos. Por favor, inténtelo más tarde.</p>";
    exit();
} catch (\PDOException $e) { // Capturar PDOException directamente si no se lanza RuntimeException
    error_log("Error fatal de conexión a la base de datos: " . $e->getMessage());
    http_response_code(500);
    echo "<h1>Error interno del servidor</h1><p>No se pudo conectar a la base de datos. Por favor, inténtelo más tarde.</p>";
    exit();
}


$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($script_name, '', $request_uri);
$path = trim($path, '/');

$routes = [
    '' => ['controller' => 'DashboardController', 'method' => 'index'],
    'panelprincipal' => ['controller' => 'DashboardController', 'method' => 'index'],
    'login' => ['controller' => 'AuthController', 'method' => 'login'],
    'cerrarsesion' => ['controller' => 'AuthController', 'method' => 'logout'],
    'consultar' => ['controller' => 'ProductController', 'method' => 'listProducts'],
    'agregar' => ['controller' => 'ProductController', 'method' => 'addProduct'],
    'editar' => ['controller' => 'ProductController', 'method' => 'editProduct'],
    'confirmar_eliminar' => ['controller' => 'ProductController', 'method' => 'confirmDelete'],
    'eliminar' => ['controller' => 'ProductController', 'method' => 'deleteProduct'],
    'mover' => ['controller' => 'MovementController', 'method' => 'registerMovement'],
    'movimientos' => ['controller' => 'MovementController', 'method' => 'history'],
    'reportes' => ['controller' => 'ReportController', 'method' => 'stockReports'],
    'reportes/graficos' => ['controller' => 'ReportController', 'method' => 'graphicReports'],
    'reportes/pdf' => ['controller' => 'ReportController', 'method' => 'generatePdf'],
    'reportes/excel' => ['controller' => 'ReportController', 'method' => 'generateExcel'],
    'crear_empleado' => ['controller' => 'UserController', 'method' => 'createUser'],
];

$controllerName = null;
$methodName = null;

if (isset($routes[$path])) {
    $controllerName = 'App\\Controllers\\' . $routes[$path]['controller'];
    $methodName = $routes[$path]['method'];
} elseif ($path === 'index.php' || $path === 'index') {
    $controllerName = 'App\\Controllers\\AuthController';
    $methodName = 'login';
}

if ($controllerName && $methodName) {
    if (class_exists($controllerName)) {
        $controller = new $controllerName($conn);
        if (method_exists($controller, $methodName)) {
            $controller->$methodName();
        } else {
            http_response_code(404);
            echo "<h1>404 Not Found</h1><p>El método solicitado no existe.</p>";
            exit();
        }
    } else {
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>El controlador solicitado no existe.</p>";
        exit();
    }
} else {
    http_response_code(404);
    echo "<h1>404 Not Found</h1><p>La página solicitada no existe.</p>";
    exit();
}

<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Movement;
use PDO;
use PDOException;
use Fpdf\Fpdf;

class MovementController extends BaseController {

    private $productModel;
    private $movementModel;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->productModel = new Product($conn);
        $this->movementModel = new Movement($conn);
    }

    public function registerMovement() {
        // Verificar si el usuario ha iniciado sesión
        if (!isset($_SESSION['usuario'])) {
            header('Location: login');
            exit();
        }

        $producto_id = $_GET['producto_id'] ?? null;
        $producto = null;
        if ($producto_id) {
            $producto = $this->productModel->getProductById($producto_id);
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
            $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
            $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
            $motivo = filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_STRING);
            
            // Validaciones
            if (!$producto_id || !$tipo || !$cantidad || $cantidad <= 0 || empty($motivo)) {
                $error = "Por favor, complete todos los campos correctamente.";
            } else {
                try {
                    $this->movementModel->addMovement($producto_id, $tipo, $cantidad, $motivo, $_SESSION['usuario']);
                    $operation = ($tipo == 'entrada') ? '+' : '-';
                    $this->productModel->updateProductQuantity($producto_id, $cantidad, $operation);
                    
                    header('Location: consultar?exito=Movimiento registrado correctamente');
                    exit();
                } catch(PDOException $e) {
                    $error = "Error al registrar movimiento: " . $e->getMessage();
                }
            }
        }

        $title = $producto ? "Movimiento: " . htmlspecialchars($producto['nombre']) : "Registrar Movimiento";
        $all_products = $this->productModel->getAllProducts(); // Para el select si no se pasa producto_id
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/movements/register.php';
    }

    public function history() {
        // Verificar si el usuario ha iniciado sesión
        if (!isset($_SESSION['usuario'])) {
            header('Location: login');
            exit();
        }

        $tipo_filtro = filter_input(INPUT_GET, 'tipo', FILTER_SANITIZE_STRING) ?? '';
        $producto_filtro = filter_input(INPUT_GET, 'producto', FILTER_VALIDATE_INT) ?? '';

        $movimientos = $this->movementModel->getMovementHistory($tipo_filtro, $producto_filtro);
        $all_products = $this->productModel->getAllProducts(); // Para el select de filtro

        $title = "Historial de Movimientos";
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/movements/history.php';
    }
}

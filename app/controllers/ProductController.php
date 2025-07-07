<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Movement; // Usar el namespace completo
use PDO;
use PDOException;

class ProductController extends BaseController {
    private $productModel;
    private $movementModel;

    public function __construct(PDO $conn) {
        parent::__construct($conn); // Llamar al constructor de la clase base
        $this->productModel = new Product($conn);
        $this->movementModel = new Movement($conn);
    }

    public function listProducts() {
        $this->checkAuth();

        $busqueda = filter_input(INPUT_GET, 'busqueda', FILTER_SANITIZE_STRING) ?? '';
        $productos = $this->productModel->getAllProducts($busqueda);
        $title = "Consultar Inventario";

        $this->render('products/list', ['title' => $title, 'productos' => $productos]);
    }

    public function addProduct() {
        $this->checkAuth();

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken($_POST['csrf_token'] ?? '');

            $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
            $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
            $ubicacion = filter_input(INPUT_POST, 'ubicacion', FILTER_SANITIZE_STRING);
            $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
            $stock_minimo = filter_input(INPUT_POST, 'stock_minimo', FILTER_VALIDATE_INT);

            if (empty($codigo) || empty($nombre) || empty($ubicacion) || $cantidad === false || $cantidad < 0 || $stock_minimo === false || $stock_minimo < 0) {
                $error = "Por favor, complete todos los campos requeridos y asegúrese de que la cantidad y el stock mínimo sean números válidos.";
            } else {
                try {
                    $producto_id = $this->productModel->addProduct($codigo, $nombre, $descripcion, $categoria, $ubicacion, $cantidad, $stock_minimo);
                    $this->movementModel->addMovement($producto_id, 'entrada', $cantidad, 'Entrada inicial', $_SESSION['usuario']);
                    $this->redirect('consultar', ['exito' => 'Producto agregado correctamente']);
                } catch(PDOException $e) {
                    error_log("Error al agregar producto: " . $e->getMessage());
                    $error = "Error al agregar producto. Por favor, inténtelo de nuevo.";
                }
            }
        }

        $title = "Agregar Producto";
        $this->render('products/add', ['title' => $title, 'error' => $error, 'csrf_token' => $this->generateCsrfToken()]);
    }

    public function editProduct() {
        $this->checkAuth();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->redirect('consultar', ['error' => 'ID de producto no especificado']);
        }

        $producto = $this->productModel->getProductById($id);
        if (!$producto) {
            $this->redirect('consultar', ['error' => 'Producto no encontrado']);
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken($_POST['csrf_token'] ?? '');

            $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
            $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
            $ubicacion = filter_input(INPUT_POST, 'ubicacion', FILTER_SANITIZE_STRING);
            $stock_minimo = filter_input(INPUT_POST, 'stock_minimo', FILTER_VALIDATE_INT);

            if (empty($codigo) || empty($nombre) || empty($ubicacion) || $stock_minimo === false || $stock_minimo < 0) {
                $error = "Por favor, complete todos los campos requeridos y asegúrese de que el stock mínimo sea un número válido.";
            } else {
                try {
                    $this->productModel->updateProduct($id, $codigo, $nombre, $descripcion, $categoria, $ubicacion, $stock_minimo);
                    $this->redirect('consultar', ['exito' => 'Producto actualizado correctamente']);
                } catch(PDOException $e) {
                    error_log("Error al actualizar producto: " . $e->getMessage());
                    $error = "Error al actualizar producto. Por favor, inténtelo de nuevo.";
                }
            }
        }

        $title = "Editar Producto";
        $this->render('products/edit', ['title' => $title, 'error' => $error, 'producto' => $producto, 'csrf_token' => $this->generateCsrfToken()]);
    }

    public function confirmDelete() {
        $this->checkAuth();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->redirect('consultar', ['error' => 'ID de producto no especificado']);
        }

        $producto = $this->productModel->getProductById($id);
        if (!$producto) {
            $this->redirect('consultar', ['error' => 'Producto no encontrado']);
        }

        $title = "Confirmar Eliminación";
        $this->render('products/confirm_delete', ['title' => $title, 'producto' => $producto, 'csrf_token' => $this->generateCsrfToken()]);
    }

    public function deleteProduct() {
        $this->checkAuth();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $csrf_token = filter_input(INPUT_GET, 'csrf_token', FILTER_SANITIZE_STRING); // Token en GET para eliminación

        if (!$id) {
            $this->redirect('consultar', ['error' => 'ID de producto no especificado para eliminar']);
        }
        $this->verifyCsrfToken($csrf_token); // Verificar token CSRF

        try {
            $this->productModel->deleteProduct($id);
            $this->redirect('consultar', ['exito' => 'Producto eliminado correctamente']);
        } catch(PDOException $e) {
            error_log("Error al eliminar producto: " . $e->getMessage());
            $this->redirect('consultar', ['error' => 'Error al eliminar producto: ' . $e->getMessage()]);
        }
    }
}

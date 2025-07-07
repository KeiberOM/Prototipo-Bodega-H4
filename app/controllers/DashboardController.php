<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Movement;
use PDO;

class DashboardController extends BaseController {
    private $productModel;
    private $movementModel;

    public function __construct(PDO $conn) {
        parent::__construct($conn); // Llamar al constructor de la clase base
        $this->productModel = new Product($conn);
        $this->movementModel = new Movement($conn);
    }

    public function index() {
    $this->checkAuth();
    
    $products_in_stock_count = $this->productModel->getProductsInStockCount();
    $out_of_stock_products_count = $this->productModel->getOutOfStockProductsCount();
    
    // Obtener el usuario actual y su rol
    $currentUser = $_SESSION['usuario'] ?? '';
    $userRole = $_SESSION['rol'] ?? '';
    
    // Determinar si filtrar por usuario
    $userId = ($userRole === 'empleado') ? $currentUser : null;
    
    // Pasar el userId a ambos métodos
    $today_movements_count = $this->movementModel->getTodayMovementsCount($userId);
    $recent_movements = $this->movementModel->getRecentMovements(5, $userId);
    
    $title = "Panel Principal";
    $this->render('dashboard/index', [
        'title' => $title,
        'products_in_stock_count' => $products_in_stock_count,
        'out_of_stock_products_count' => $out_of_stock_products_count,
        'today_movements_count' => $today_movements_count,
        'recent_movements' => $recent_movements
    ]);
}

}

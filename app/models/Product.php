<?php
namespace App\Models; // Añadir namespace

use PDO;

class Product {
    private $conn;
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }
    public function getAllProducts($busqueda = '') {
        $query = "SELECT * FROM productos WHERE nombre LIKE :busqueda OR codigo LIKE :busqueda ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':busqueda' => '%' . $busqueda . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($codigo, $nombre, $descripcion, $categoria, $ubicacion, $cantidad, $stock_minimo) {
        $stmt = $this->conn->prepare("INSERT INTO productos (codigo, nombre, descripcion, categoria, ubicacion, cantidad, stock_minimo)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$codigo, $nombre, $descripcion, $categoria, $ubicacion, $cantidad, $stock_minimo]);
    }

    public function updateProduct($id, $codigo, $nombre, $descripcion, $categoria, $ubicacion, $stock_minimo) {
        $stmt = $this->conn->prepare("UPDATE productos SET codigo = ?, nombre = ?, descripcion = ?,
                              categoria = ?, ubicacion = ?, stock_minimo = ? WHERE id = ?");
        return $stmt->execute([$codigo, $nombre, $descripcion, $categoria, $ubicacion, $stock_minimo, $id]);
    }

    public function deleteProduct($id) {
        // Eliminar movimientos asociados primero (para evitar errores de FK)
        $stmt = $this->conn->prepare("DELETE FROM movimientos WHERE producto_id = ?");
        $stmt->execute([$id]);

        $stmt = $this->conn->prepare("DELETE FROM productos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateProductQuantity($productId, $quantity, $operation) {
        $stmt = $this->conn->prepare("UPDATE productos SET cantidad = cantidad $operation ? WHERE id = ?");
        $stmt->execute([$quantity, $productId]);
        // Asegurar que no haya cantidades negativas
        $this->conn->query("UPDATE productos SET cantidad = 0 WHERE cantidad < 0");
        return $stmt->rowCount();
    }

    public function getProductsBelowMinStock() {
        $stmt = $this->conn->query("SELECT * FROM productos WHERE cantidad <= stock_minimo ORDER BY cantidad ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOutOfStockProducts() {
        $stmt = $this->conn->query("SELECT * FROM productos WHERE cantidad <= 0 ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSufficientStockProducts() {
        $stmt = $this->conn->query("SELECT * FROM productos WHERE cantidad > stock_minimo ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsInStockCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM productos WHERE cantidad > 0");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getOutOfStockProductsCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM productos WHERE cantidad <= 0");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getProductsCountByCategory() {
        $stmt = $this->conn->query("SELECT categoria, COUNT(*) as total FROM productos GROUP BY categoria");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

<?php
namespace App\Models;

use PDO;
class Movement {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addMovement($productId, $type, $quantity, $reason, $user) {
        $stmt = $this->conn->prepare("INSERT INTO movimientos (producto_id, tipo, cantidad, motivo, usuario) 
                               VALUES (?, ?, ?, ?, ?)");
        // Asegurarse de que los datos estén saneados antes de la inserción
        return $stmt->execute([$productId, $type, $quantity, $reason, $user]);
    }

    public function getMovementHistory($type = '', $productId = '') {
        $sql = "SELECT m.id, m.fecha, p.nombre as producto, m.tipo, m.cantidad, m.motivo, m.usuario 
                FROM movimientos m 
                JOIN productos p ON m.producto_id = p.id";

        $conditions = [];
        $params = [];

        if (!empty($type)) {
            $conditions[] = "m.tipo = :tipo";
            $params[':tipo'] = $type;
        }
        if (!empty($productId)) {
            $conditions[] = "m.producto_id = :producto_id";
            $params[':producto_id'] = $productId;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY m.fecha DESC";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Modificación aquí: Añadir $excludeAdminMovements
    public function getRecentMovements($limit = 5, $userId = null) {
    $sql = "SELECT m.fecha, p.nombre as nombre_producto, m.tipo, m.cantidad, m.usuario, u.rol 
            FROM movimientos m 
            JOIN productos p ON m.producto_id = p.id 
            JOIN usuarios u ON m.usuario = u.usuario";
    
    $conditions = [];
    $params = [];
    
    // Si se proporciona un ID de usuario, filtrar solo sus movimientos
    if ($userId !== null) {
        $conditions[] = "m.usuario = :usuario";
        $params[':usuario'] = $userId;
    }
    
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $sql .= " ORDER BY m.fecha DESC LIMIT :limit";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getTodayMovementsCount($userId = null) {
    $sql = "SELECT COUNT(*) as total 
            FROM movimientos m
            JOIN usuarios u ON m.usuario = u.usuario
            WHERE DATE(m.fecha) = CURDATE()";
    
    if ($userId !== null) {
        $sql .= " AND m.usuario = :usuario";
    }
    
    $stmt = $this->conn->prepare($sql);
    
    if ($userId !== null) {
        $stmt->bindValue(':usuario', $userId);
    }
    
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

    // Nuevo método para obtener el conteo de movimientos por tipo (para gráficos)
    public function getMovementsCountByType() {
        $stmt = $this->conn->query("SELECT tipo, COUNT(*) as total FROM movimientos GROUP BY tipo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

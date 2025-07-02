<?php
namespace App\Models; // Añadir namespace

use PDO;

class User {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function authenticate($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['contrasena'])) {
            return $user;
        }
        return false;
    }

    public function addUser($username, $password, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hashedPassword, $role]);
    }

    // Nuevo método para obtener un usuario por su nombre de usuario
    public function getUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        $stmt = $this->conn->query("SELECT id, usuario, rol FROM usuarios ORDER BY usuario");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ... (otros métodos)
}

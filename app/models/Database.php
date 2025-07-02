<?php
namespace App\Models; // Añadir namespace

use PDO;
use PDOException;

class Database {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Leer desde variables de entorno
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->dbname = $_ENV['DB_NAME'] ?? 'bodega_h4';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? '';

        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Fetch por defecto como array asociativo
            $this->conn->exec("SET NAMES utf8"); // Asegurar UTF-8
        } catch(PDOException $e) {
            // En un entorno de producción, no mostrar el mensaje de error directamente al usuario
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            die("Error de conexión a la base de datos. Por favor, inténtelo más tarde.");
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

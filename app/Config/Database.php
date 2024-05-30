<?php

namespace App\Config;

use PDO;

class Database {
    private static $instance;
    private $conn;

    // Cambiar el modificador de acceso del constructor a private
    private function __construct() {
        $host = env->get('DB_HOST');
        $dbname = env->get('DB_NAME');
        $username = env->get('DB_USER');
        $password = env->get('DB_PASS');

        $this->conn = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(): self {
        if (!self::$instance) {
            // Crear una nueva instancia utilizando el constructor público
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Método para obtener la conexión PDO
    public function getConnection(): PDO {
        return $this->conn;
    }
}

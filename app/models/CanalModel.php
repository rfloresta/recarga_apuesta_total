<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class CanalModel {
    private $db;

    public function __construct() {
        $instance = Database::getInstance();
        $this->db = $instance->getConnection();
    }

    public function listar_canales_comunicacion() {
        $stmt = $this->db->prepare('call listar_canales_comunicacion()');
        $stmt->execute();

        // Obtener los resultados
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar los resultados
        return $results;
    }

}

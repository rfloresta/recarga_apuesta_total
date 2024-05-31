<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class ClienteModel {
    private $db;

    public function __construct() {
        $instance = Database::getInstance();
        $this->db = $instance->getConnection();
    }

    public function consultar_cliente_por_player_id(int $playerID): Array {
        $stmt = $this->db->prepare('call consultar_cliente_por_player_id(?)');
        $stmt->bindParam(1, $playerID, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener los resultados
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar los resultados
        return $results;
    }


}

<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class RecargaModel {
    private $db;

    public function __construct() {
        $instance = Database::getInstance();
        $this->db = $instance->getConnection();
    }

    public function consultar_recargas_por_player_id(int $playerID): Array {
        $stmt = $this->db->prepare('call consultar_recargas_por_player_id(?)');
        $stmt->bindParam(1, $playerID, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener los resultados
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar los resultados
        return $results;
    }

    public function realizar_recarga(int $usuarioId, int $playerId, float $monto, int $bancoId, int $canalId, string $fotoVoucher): Array {
        $stmt = $this->db->prepare('CALL realizar_recarga(?, ?, ?, ?, ?, ?)');
        $stmt->bindParam(1, $usuarioId, PDO::PARAM_INT);
        $stmt->bindParam(2, $playerId, PDO::PARAM_INT);
        $stmt->bindParam(3, $monto, PDO::PARAM_STR);
        $stmt->bindParam(4, $bancoId, PDO::PARAM_INT);
        $stmt->bindParam(5, $canalId, PDO::PARAM_INT);
        $stmt->bindParam(6, $fotoVoucher, PDO::PARAM_STR);

        $stmt->execute();

        // Obtener los resultados
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar los resultados
        return $results;
    }

    public function actualizar_recarga(int $id, int $usuarioId, float $nuevoMonto, int $nuevoBancoID, int $nuevoCanalID): Array {
        $stmt = $this->db->prepare('CALL actualizar_recarga(?, ?, ?, ?, ?)');
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $usuarioId, PDO::PARAM_INT);
        $stmt->bindParam(3, $nuevoMonto, PDO::PARAM_STR);
        $stmt->bindParam(4, $nuevoBancoID, PDO::PARAM_INT);
        $stmt->bindParam(5, $nuevoCanalID, PDO::PARAM_INT);

        $stmt->execute();

        // Obtener los resultados
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar los resultados
        return $results;
    }
}

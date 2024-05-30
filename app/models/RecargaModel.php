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

    public function getHistorial($recargaId) {
        $stmt = $this->db->prepare('call ConsultarRecargasPorPlayerID(?)');
        $stmt->bindParam(1, $recargaId, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener los resultados
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar los resultados
        return $results;
    }

    public function create($usuarioId, $playerId, $monto, $bancoId, $canalId, $fotoVoucher) {
        $stmt = $this->db->prepare('CALL RealizarRecarga(?, ?, ?, ?, ?, ?)');
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
        return $results[0];
    }

    public function update($id, $usuarioId, $nuevoMonto, $nuevoBancoID, $nuevoCanalID) {
        $stmt = $this->db->prepare('CALL ActualizarRecarga(?, ?, ?, ?, ?)');
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $usuarioId, PDO::PARAM_INT);
        $stmt->bindParam(3, $nuevoMonto, PDO::PARAM_STR);
        $stmt->bindParam(4, $nuevoBancoID, PDO::PARAM_INT);
        $stmt->bindParam(5, $nuevoCanalID, PDO::PARAM_INT);

        $stmt->execute();

        // Obtener los resultados
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar los resultados
        return $results[0];
    }
}

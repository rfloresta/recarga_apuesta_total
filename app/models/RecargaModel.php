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
        $stmt = $this->db->prepare( 'call ConsultarRecargasPorPlayerID(?)' );
        $stmt->bindParam( 1, $recargaId, PDO::PARAM_INT );
        $stmt->execute();

        // Obtener los resultados
        $results = $stmt->fetchAll( PDO::FETCH_ASSOC );

        // Mostrar los resultados
        return $results;
    }

    public function create( $clienteId, $monto, $bancoId, $canalId, $fotoVoucher ) {
        $stmt = $this->db->prepare( 'CALL RealizarRecarga(?, ?, ?, ?, ?)' );
        $stmt->bindParam( 1, $clienteId, PDO::PARAM_INT );
        $stmt->bindParam( 2, $monto, PDO::PARAM_STR );
        $stmt->bindParam( 3, $bancoId, PDO::PARAM_INT );
        $stmt->bindParam( 4, $canalId, PDO::PARAM_INT );
        $stmt->bindParam( 5, $fotoVoucher, PDO::PARAM_LOB );

        $stmt->execute();

        // Obtener los resultados
        $results = $stmt->fetchAll( PDO::FETCH_ASSOC );

        // Mostrar los resultados
        return $results;
    }
}

<?php

namespace App\Controllers;

use App\Models\RecargaModel;
use App\Utils\Common;
use PDO;

class RecargaController {
    private $recargaModel;

    public function __construct() {
        $this->recargaModel = new RecargaModel();
    }

    public function getHistorial($player_id) {
        $responseObj =  Common::buildObjResponse();

        try {
            $result = $this->recargaModel->getHistorial($player_id);
            Common::handleDatabaseQueryErrors($result);

            $responseObj->message = $result[0]['mensaje'];
            $responseObj->code = $result[0]['code'];
            $status = 200;
        } catch (\Throwable $th) {
            $responseObj->message = "Error en nuestros servicios";
            $status = Common::validateHttpCode($th->getCode());
        }

        Common::response($responseObj, $status);
    }

    public function store($request) {

        $requestData = json_decode(file_get_contents("php://input"));
        $responseObj =  Common::buildObjResponse();

        try {
            $requiredFields = ['player_id', 'monto', 'banco_id', 'canal_id', 'foto_voucher'];
        
            foreach ($requiredFields as $field) {
                if (!isset($requestData->$field)) {
                    http_response_code(400);
                    echo json_encode(array("message" => "Todos los campos son requeridos."));
                    return;
                }
            }
    
            $result = $this->recargaModel->create(
                $requestData->player_id,
                $requestData->monto,
                $requestData->banco_id,
                $requestData->canal_id,
                $requestData->foto_voucher
            );

            Common::handleDatabaseQueryErrors($result);

            $responseObj->message = $result[0]['mensaje'];
            $responseObj->code = $result[0]['code'];

            $status = 201;

        } catch (\Throwable $th) {
            $responseObj->message = "Error en nuestros servicios";
            $status = Common::validateHttpCode($th->getCode());
        }

        Common::response($responseObj, $status);
    }
}
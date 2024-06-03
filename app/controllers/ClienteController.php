<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Helpers\Common;
use PDO;
use Exception;

class ClienteController extends Controller{
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new ClienteModel();
    }

    public function findByPlayerID(int $playerID) {
        $responseObj =  Common::buildObjResponse();

        try {
            $result = $this->clienteModel->consultar_cliente_por_player_id($playerID);
            Common::handleDatabaseQueryErrors($result);
            if(count($result) == 0){
                throw new Exception("No se encontraron datos", 400);
            }
            $responseObj->data = $result[0];
            $responseObj->success = TRUE;
            $status = 200;
        } catch (\Throwable $th) {
            $responseObj->message = $th->getMessage();
            $status = Common::validateHttpCode(intval($th->getCode()));
        }

        $this->response($responseObj, $status);
    }
}
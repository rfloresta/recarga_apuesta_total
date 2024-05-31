<?php

namespace App\Controllers;

use App\Models\CanalModel;
use App\Utils\Common;
use PDO;
use Exception;

class CanalController extends Controller{
    private $canalModel;

    public function __construct() {
        $this->canalModel = new CanalModel();
    }

    public function canales() {
        $responseObj =  Common::buildObjResponse();

        try {
            $result = $this->canalModel->listar_canales_comunicacion();
            Common::handleDatabaseQueryErrors($result);
            if(count($result) == 0){
                throw new Exception("No se encontraron datos", 400);
            }
            $responseObj->data = $result;
            $responseObj->success = TRUE;
            $status = 200;
        } catch (\Throwable $th) {
            $responseObj->message = $th->getMessage();
            $status = Common::validateHttpCode(intval($th->getCode()));
        }

        $this->response($responseObj, $status);
    }
}
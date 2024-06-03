<?php

namespace App\Controllers;

use App\Models\BancoModel;
use App\Helpers\Common;
use PDO;
use Exception;

class BancoController extends Controller{
    private $bancoModel;

    public function __construct() {
        $this->bancoModel = new BancoModel();
    }

    public function bancos() {
        $responseObj =  Common::buildObjResponse();

        try {
            $result = $this->bancoModel->listar_bancos();
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
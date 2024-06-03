<?php

namespace App\Controllers;

use App\Models\RecargaModel;
use App\Helpers\Common;
use PDO;
use Exception;

class RecargaController extends Controller{
    private $recargaModel;

    public function __construct() {
        $this->recargaModel = new RecargaModel();
    }

    public function findAllByPlayerID(int $player_id) {
        $responseObj =  Common::buildObjResponse();

        try {
            $result = $this->recargaModel->consultar_recargas_por_player_id($player_id);
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

    public function store() {
        $responseObj =  Common::buildObjResponse();

        try {
            // Obtener el JSON del cuerpo de la solicitud
            $data = $this->decodeJsonBody();
            
            $requiredFields = ['usuario_id', 'player_id', 'monto', 'banco_id', 'canal_id', 'foto_voucher'];
        
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    throw new Exception("Todos los campos son requeridos.", 400);
                }
            }
    
            $result = $this->recargaModel->realizar_recarga(
                $data['usuario_id'],
                $data['player_id'],
                $data['monto'],
                $data['banco_id'],
                $data['canal_id'],
                $data['foto_voucher']
            );

            Common::handleDatabaseQueryErrors($result);

            $responseObj->message = $result[0]['msg_info'];
            $responseObj->code = $result[0]['msg_code'];
            $responseObj->success = TRUE;

            $status = 201;

        } catch (\Throwable $th) {
            $responseObj->message = "Error en nuestros servicios";
            $status = Common::validateHttpCode(intval($th->getCode()));
        }

        $this->response($responseObj, $status);
    }

    public function update($id) {
        $responseObj =  Common::buildObjResponse();

        try {

            // Obtener el JSON del cuerpo de la solicitud
            $data = $this->decodeJsonBody();
            $data['id'] = $id;

            $requiredFields = ['id', 'usuario_id', 'monto', 'banco_id', 'canal_id'];
        
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    throw new Exception("Todos los campos son requeridos.", 400);
                }
            }
    
            $result = $this->recargaModel->actualizar_recarga(
                $data['id'],
                $data['usuario_id'],
                $data['monto'],
                $data['banco_id'],
                $data['canal_id']
            );

            Common::handleDatabaseQueryErrors($result);

            $responseObj->message = $result[0]['msg_info'];
            $responseObj->code = $result[0]['msg_code'];
            $responseObj->success = TRUE;

            $status = 200;

        } catch (\Throwable $th) {
            $responseObj->message = "Error en nuestros servicios";
            $status = Common::validateHttpCode(intval($th->getCode()));
        }

        $this->response($responseObj, $status);
    }
}
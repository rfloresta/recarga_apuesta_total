<?php

namespace App\Controllers;

use App\Models\RecargaModel;
use App\Utils\Common;
use PDO;
use Exception;

class RecargaController extends Controller{
    private $recargaModel;

    public function __construct() {
        $this->recargaModel = new RecargaModel();
    }

    public function getHistorial(int $player_id) {
        $responseObj =  Common::buildObjResponse();

        try {
            $result = $this->recargaModel->getHistorial($player_id);
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

            // Verificar si la decodificación fue exitosa
            if ($data === null) {
                // JSON inválido
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'JSON inválido']);
                exit;
            }
            
            $requiredFields = [ 'usuario_id', 'player_id', 'monto', 'banco_id', 'canal_id', 'foto_voucher'];
        
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    throw new Exception("Todos los campos son requeridos.", 400);
                }
            }
    
            $result = $this->recargaModel->create(
                $data['usuario_id'],
                $data['player_id'],
                $data['monto'],
                $data['banco_id'],
                $data['canal_id'],
                $data['foto_voucher']
            );

            Common::handleDatabaseQueryErrors($result);

            $responseObj->message = $result['msg_info'];
            $responseObj->code = $result['msg_code'];
            $responseObj->success = TRUE;

            $status = 201;

        } catch (\Throwable $th) {
            $responseObj->message = "Error en nuestros servicios";
            $responseObj->message = $th->getMessage();
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
    
            $result = $this->recargaModel->update(
                $data['id'],
                $data['usuario_id'],
                $data['monto'],
                $data['banco_id'],
                $data['canal_id']
            );

            Common::handleDatabaseQueryErrors($result);

            $responseObj->message = $result['msg_info'];
            $responseObj->code = $result['msg_code'];
            $responseObj->success = TRUE;

            $status = 200;

        } catch (\Throwable $th) {
            $responseObj->message = "Error en nuestros servicios";
            $responseObj->message = $th->getMessage();
            $status = Common::validateHttpCode(intval($th->getCode()));
        }

        $this->response($responseObj, $status);
    }
}
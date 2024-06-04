<?php

namespace App\Core\UseCase;

use App\Core\Repositories\RecargaRepository;

Class RecargaUseCase {
    private $_recargaRepository;

    public function __construct(RecargaRepository $recargaRepository) {
        $this->$_recargaRepository = $recargaRepository;
    }

    public function recargasCliente() {
        $responseObj =  Common::buildObjResponse();

        try {
            $result = $this->$_recargaRepository->findByPlayerID($player_id);
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
<?php

namespace App\Controllers;

use stdClass;
class Controller{
    public function index(){
        include BASE_PATH . '/app/routes.php';
    }

    /**
     * Decodifica automáticamente el JSON en las solicitudes POST.
     *
     * @return mixed Los datos decodificados del cuerpo de la solicitud POST.
     */
    protected function decodeJsonBody() {
        return json_decode(file_get_contents("php://input"), true);
    }

    /**
    * Crea la respuesta de los servicios.
    *
    * @param stdClass $data Los datos a ser codificados como JSON.
    * @param int $status El código de estado HTTP de la respuesta.
    * @return void
    */
    public static function response(stdClass $data, int $status): void {
        header('Content-Type: application/json');

        http_response_code($status);
        $json = json_encode($data);
        if ($json === false) {
            // Si la codificación JSON falla, retorna un mensaje de error
            echo json_encode(array('error' => 'Error en la codificación JSON'));
        } else {
            echo $json;
        }
    }
}
<?php

namespace App\Utils;

use stdClass;

class Common {
    /**
    * Crea una estructura base para los datos de respuesta de los servicios.
    * @return stdClass
    */
    public static function buildObjResponse(): stdClass {
        $response          = new stdClass();
        $response->success = false;
        $response->message = '';
        $response->code    = 0;
        $response->data    = array();

        return $response;
    }

    /**
    * Valida un código de estado HTTP.
    *
    * @param int $code El código de estado HTTP a validar.
    * @return int El código de estado HTTP válido.
    */
    public static function validateHttpCode(int $code): int {
        if ($code >= 100 && $code < 600) {
            return $code;
        } else {
            return 500;
        }
    }

    /**
    * Crea la respuesta de los servicios.
    *
    * @param array $data Los datos a ser codificados como JSON.
    * @param int $status El código de estado HTTP de la respuesta.
    * @return void
    */
    public static function response(stdClass $data, int $status): void {
        http_response_code($status);
        $json = json_encode($data);
        if ($json === false) {
            // Si la codificación JSON falla, retorna un mensaje de error
            echo json_encode(array('error' => 'Error en la codificación JSON'));
        } else {
            echo $json;
        }
    }

    /**
    * Maneja los errores de las consultas a la base de datos.
    *
    * @param array $result El resultado de la consulta a la base de datos.
    * @throws Exception Si se encuentran errores en la consulta.
    * @return array El resultado de la consulta a la base de datos.
    */
    public static function handleDatabaseQueryErrors(array $result): array {
        if (!is_array($result)) {
            throw new Exception('La respuesta de la base de datos está vacía', 400);
        } else {
            foreach ($result as $row) {
                if (isset($row['code']) && intval($row['code']) < 0) {
                    $error = 'Error SQL';

                    if (isset($row['mensaje'])) {
                        $error = $row['mensaje'];
                    }
                    throw new Exception($error, 400);
                }
            }
        }
        return $result;
    }

}

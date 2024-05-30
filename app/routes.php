<?php

// Importar los controladores necesarios
require_once 'controllers/RecargasController.php';

// Definir las rutas
$routes = [
    'GET' => [
        '/recargas' => 'RecargasController@index',
        '/recargas/{id}' => 'RecargasController@find',
        '/historial/{player_id}' => 'RecargasController@showHistorial',
    ],
    'POST' => [
        '/recargas' => 'RecargasController@store',
    ],
    'PUT' => [
        '/recargas/{id}' => 'RecargasController@update',
    ]
];

return $routes;

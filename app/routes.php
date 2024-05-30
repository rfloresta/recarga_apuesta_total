<?php

use App\Controllers\RecargasController;

// Definir las rutas
$routes = [
    'GET' => [
        '/recargas/{id}' => [RecargasController::class, 'find'],
        '/historial/{player_id}' => [RecargasController::class, 'showHistorial'],
    ],
    'POST' => [
        '/recargas' => [RecargasController::class, 'store'],
    ],
    'PUT' => [
        '/recargas/{id}' => [RecargasController::class, 'update'],
    ],
];

return $routes;

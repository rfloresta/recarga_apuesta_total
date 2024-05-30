<?php

// Importar las clases necesarias
use App\Controllers\RecargasController;

// Definir las rutas
$routes = [
    'GET' => [
        '/recargas' => [RecargasController::class, 'index'],
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

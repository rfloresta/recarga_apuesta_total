<?php

use App\Controllers\RecargaController;
use App\Controllers\CanalController;
use App\Controllers\BancoController;
use Bramus\Router\Router;

$router = new Router();

$recargaController = new RecargaController();

// Ruta para las Recargas
$router->get('/recargas/historial/{player_id}', function ($player_id) use ($recargaController) {
    $recargaController->getHistorial($player_id);
});

$router->post('/recargas', function () use ($recargaController) {
    $recargaController->store();
});


$router->put('/recargas/(\d+)', function ($id) use ($recargaController) {
    $recargaController->update($id);
});

// Ruta para los canales
$router->get('/canales', function () {
    $canalController = new CanalController();
    $canalController->canales();
});

// Ruta para los bancos
$router->get('/bancos', function () {
    $bancoController = new BancoController();
    $bancoController->bancos();
});

$router->run();

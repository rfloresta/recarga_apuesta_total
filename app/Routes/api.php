<?php

use App\Controllers\RecargaController;
use App\Controllers\CanalController;
use App\Controllers\BancoController;
use App\Controllers\ClienteController;
use App\Helpers\Router;

$router = new Router();

// Ruta para las Recargas
$router->get('/recargas/player/(\d+)', function ($player_id) {
    $recargaController = new RecargaController();
    $recargaController->findAllByPlayerID($player_id);
});

$router->post('/recargas', function () {
    $recargaController = new RecargaController();
    $recargaController->store();
});

$router->put('/recargas/(\d+)', function ($id) {
    $recargaController = new RecargaController();
    $recargaController->update($id);
});

// Ruta para obtener la lista de canales de comunicación
$router->get('/canales', function () {
    $canalController = new CanalController();
    $canalController->canales();
});

// Ruta para obtener la lista de bancos
$router->get('/bancos', function () {
    $bancoController = new BancoController();
    $bancoController->bancos();
});

// Ruta para obtener los clientes
$router->get('/cliente/player/(\d+)', function ($player_id) {
    $clienteController = new ClienteController();
    $clienteController->findByPlayerID($player_id);
});

$router->run();

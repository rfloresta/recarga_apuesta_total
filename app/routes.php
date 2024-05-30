<?php

use App\Controllers\RecargaController;
use Bramus\Router\Router;

$router = new Router();

$recargaController = new RecargaController();

$router->get('/recargas/(\d+)', function ($player_id) use ($recargaController) {
    $recargaController->getHistorial($player_id);
});

$router->post('/recargas', function () use ($recargaController) {
    $recargaController->store();
});

$router->put('/recargas/(\d+)', function ($id) use ($recargaController) {
    $recargaController->update($id);
});

$router->run();

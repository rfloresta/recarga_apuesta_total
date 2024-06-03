<?php
// Define la constante BASE_PATH
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';

use App\Config\Database;
use App\Config\Environment;
use App\Controllers\Controller;

// Carga las variables de entorno
$environment = Environment::load(BASE_PATH . '/.env');
define('$ENV', $environment);

// Cargamos las rutas
$controller = new Controller();
$controller->index();

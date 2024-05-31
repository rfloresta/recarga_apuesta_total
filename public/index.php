<?php
require_once BASE_PATH.'/vendor/autoload.php';
require_once BASE_PATH.'/config/env.php';

use App\Config\Database;
use App\Controllers\Controller;

// Define la constante BASE_PATH
define('BASE_PATH', dirname(__DIR__));

// Carga las variables de entorno
$environment = EnvironmentLoader::load(BASE_PATH . '/.env');
define('env', $environment);

// Cargamos las rutas
$controller = new Controller();
$controller->index();

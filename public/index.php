<?php

// Define la constante BASE_PATH
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';


// Importa las clases necesarias
require_once BASE_PATH.'/config/env.php';
// use Config\EnvironmentLoader;

use App\Config\Database;

use App\DI\DIContainer;

// Carga las rutas
use App\Controllers\Controller;

// Carga las variables de entorno
$environment = EnvironmentLoader::load(BASE_PATH . '/.env');
define('env', $environment);

// $dbHost = $environment->get('DB_HOST');
// $dbName = $environment->get('DB_NAME');
// $dbUser = $environment->get('DB_USER');
// $dbPass = $environment->get('DB_PASS');
// Database::getInstance($dbHost, $dbName, $dbUser, $dbPass);

// Crea una instancia del controlador y llama al método index
$controller = new Controller();
$controller->index();

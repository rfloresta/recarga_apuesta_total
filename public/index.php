<?php

// Cargar el archivo de configuración de la base de datos
require_once 'config/database.php';

// Cargar las rutas
$routes = require_once 'app/routes.php';

// Obtener la solicitud HTTP
$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Buscar la ruta en las rutas definidas
$route_found = false;
foreach ($routes[$request_method] as $route => $controller_action) {
    // Convertir la ruta en una expresión regular para hacer coincidencias
    $route_pattern = str_replace('/', '\/', $route);
    $route_pattern = preg_replace('/{[a-zA-Z0-9]+}/', '[a-zA-Z0-9]+', $route_pattern);

    if (preg_match('/^' . $route_pattern . '$/', $request_uri)) {
        // Extraer el nombre del controlador y el método
        list($controller_name, $method_name) = explode('@', $controller_action);

        // Incluir el controlador
        require_once 'app/controllers/' . $controller_name . '.php';

        // Instanciar el controlador y llamar al método correspondiente
        $controller = new $controller_name();
        $controller->$method_name();
        
        // Marcar que se encontró la ruta
        $route_found = true;
        break;
    }
}

// Si no se encuentra la ruta, devolver un error 404
if (!$route_found) {
    http_response_code(404);
    echo json_encode(array("message" => "Ruta no encontrada."));
}

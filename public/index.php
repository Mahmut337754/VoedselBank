<?php
/**
 * Front Controller – alle requests komen hier binnen
 */

define('APP_ROOT', dirname(__DIR__));

// Config laden (definieert constanten + autoloader)
require_once APP_ROOT . '/app/config/config.php';

// Sessie starten
session_name(SESSION_NAME);
session_start();

// Routes laden
$routes = require APP_ROOT . '/app/config/routes.php';

// Haal het pad op (zonder query string)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (isset($routes[$uri])) {
    $controllerName = $routes[$uri]['controller'];
    $methodName     = $routes[$uri]['method'];

    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    echo '<h1>404 – Pagina niet gevonden</h1>';
}
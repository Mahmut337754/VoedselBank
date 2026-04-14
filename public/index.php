<?php
 
/**
 * Front Controller – alle requests komen hier binnen
 */
 
// Config laden (definieert constanten + autoloader)
require_once __DIR__ . '/../app/config/config.php';
 
// Sessie starten
session_name(SESSION_NAME);
session_start();
 
// Routes laden
$routes = require APP_ROOT . '/app/config/routes.php';
 
// Huidige URI ophalen
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
 
// BASE_URL verwijderen
$uri = str_replace(BASE_URL, '/', $uri);
 
// Dubbele slashes voorkomen en root fix
$uri = '/' . trim($uri, '/');
if ($uri === '//') $uri = '/';
 
// Route uitvoeren
if (isset($routes[$uri])) {
    $controllerName = $routes[$uri]['controller'];
    $methodName     = $routes[$uri]['method'];
 
    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    echo '<h1>404 – Pagina niet gevonden</h1>';
}
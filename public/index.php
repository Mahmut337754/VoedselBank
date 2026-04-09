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
[$route, $routeParams] = resolveRoute($uri, $routes);

if ($route !== null) {
    foreach ($routeParams as $key => $value) {
        $_GET[$key] = $value;
    }

    $controllerName = $route['controller'];
    $methodName     = $route['method'];

    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    echo '<h1>404 – Pagina niet gevonden</h1>';
}

function resolveRoute(string $uri, array $routes): array
{
    if (isset($routes[$uri])) {
        return [$routes[$uri], []];
    }

    foreach ($routes as $pattern => $route) {
        if (!str_contains($pattern, '{')) {
            continue;
        }

        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', function ($matches) {
            $name = $matches[1];
            if ($name === 'id') {
                return '(?P<' . $name . '>\\d+)';
            }
            return '(?P<' . $name . '>[^/]+)';
        }, $pattern);

        if ($regex === null) {
            continue;
        }

        $regex = '#^' . $regex . '$#';
        if (preg_match($regex, $uri, $matches)) {
            $params = [];
            foreach ($matches as $key => $value) {
                if (!is_int($key)) {
                    $params[$key] = $value;
                }
            }

            return [$route, $params];
        }
    }

    return [null, []];
}
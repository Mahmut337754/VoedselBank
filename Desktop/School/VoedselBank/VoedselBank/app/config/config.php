<?php
 
/**
 * Applicatie configuratie
 */
 
// Database instellingen
defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_NAME') || define('DB_NAME', 'VoedselbankSql_dag2');
defined('DB_USER') || define('DB_USER', 'root');
defined('DB_PASS') || define('DB_PASS', '');
 
// App instellingen
defined('APP_NAME') || define('APP_NAME', 'Voedselbank Maaskantje');
// Pas dit aan op jouw lokale dev-omgeving
defined('BASE_URL') || define('BASE_URL', '/VoedselBank/public');
defined('APP_ROOT') || define('APP_ROOT', dirname(__DIR__, 2));
 
// Session instellingen
defined('SESSION_NAME') || define('SESSION_NAME', 'voedselbank_session');
 
// Autoloader voor controllers en models
spl_autoload_register(function ($class) {
    $paths = [
        APP_ROOT . '/app/controllers/' . $class . '.php',
        APP_ROOT . '/app/models/'      . $class . '.php',
    ];
 
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
 
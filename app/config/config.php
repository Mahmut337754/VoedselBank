<?php
/**
 * Applicatie configuratie
 */

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'VoedselbankSql_dag2');
define('DB_USER', 'root');
define('DB_PASS', '');

// App
define('APP_NAME', 'Voedselbank Maaskantje');
define('BASE_URL', '/');
define('APP_ROOT', dirname(__DIR__, 2));

// Session
define('SESSION_NAME', 'voedselbank_session');

// Autoloader
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

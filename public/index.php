<?php
$startTime = microtime(true);

error_reporting(E_ALL & ~E_USER_DEPRECATED);
ini_set('intl.default_locale', 'en_GB');
date_default_timezone_set('Europe/London');

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Laminas\Mvc\Application::init(require 'config/application.config.php')->run();

$time = round(microtime(true) - $startTime, 5);
\Olcs\Logging\Log\Logger::debug(
    'Internal complete',
    [
        'time' => $time,
        'url' => $_SERVER['REQUEST_URI'],
        'peak-memory-usage-MB' => (int)(memory_get_peak_usage() / 1024 / 1024),
    ]
);

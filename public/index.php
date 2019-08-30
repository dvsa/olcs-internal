<?php
$startTime = microtime(true);

error_reporting
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

include_once 'RequestLogger.php';
(new  \RequestLogger())->execute();

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();

$time = round(microtime(true) - $startTime, 5);
\Olcs\Logging\Log\Logger::debug('Internal complete', ['time' => $time, 'url' => $_SERVER['REQUEST_URI']]);

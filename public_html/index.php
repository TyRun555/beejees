<?php
/**
 * Точка входа в приложение
 */
use core\App;

require dirname(__DIR__)."/vendor/autoload.php";

spl_autoload_extensions(".php");
spl_autoload_register(function ($name) {
    include dirname(__DIR__) .'/' . str_replace("\\", '/', $name). '.php';
});

$config = require dirname(__DIR__)."/config/app.php";
$configLocal = dirname(__DIR__)."/config/app-local.php";

if (file_exists($configLocal)) {
    $config = array_merge($config, require $configLocal);
}

$app = new App($config);

if (!defined('CLI')) {
    $app->run();
}
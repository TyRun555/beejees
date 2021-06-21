<?php
use core\App;
ini_set('display_errors', 1);
error_reporting(~0);
require dirname(__DIR__)."/vendor/autoload.php";

spl_autoload_extensions(".php");
spl_autoload_register(function ($name) {
    include dirname(__DIR__) .'/' . str_replace("\\", '/', $name). '.php';
});

$config = require dirname(__DIR__)."/config/app.php";
$configLocal = dirname(__DIR__)."/config/app-local.php";

if (file_exists($configLocal)) {
    $config = array_merge($config, $configLocal);
}

$app = new App($config);

if (!defined('CLI')) {
    $app->run();
}
<?php
use core\App;

//require dirname(__DIR__)."/vendor/autoload.php";
spl_autoload_extensions(".php");
spl_autoload_register(function ($name) {
    include dirname(__DIR__) .'/' . str_replace("\\", '/', $name). '.php';
});
$config = require dirname(__DIR__)."/config/app.php";
(new App($config))->run();
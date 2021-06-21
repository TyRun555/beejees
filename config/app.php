<?php
/**
 * Файл конфигурации системы, все, не требующие защиты, параметры приложения, можно размещать здесь
 * Параметры подключения к бд и другие уязвимы данные необходимо размещать в файле ./app-local.php
 */
return [
    'dbParams' => [
        'driver'   => 'pdo_mysql',
        'user'     => 'root',
        'password' => 'root',
        'dbname'   => 'beejee_app'
    ],
    'doctrineDevMode' =>  false,
    'routes' => [
        '/' => 'site/index',
        '/login' => 'site/login',
        '/logout' => 'site/logout',
        '/admin' => 'site/admin',
        '/<controller>/<action>/<id>' => '<controller>/<action>',
        '/<controller>/<action>' => '<controller>/<action>'
    ],
    'cookieSalt' => 'sdfsdf2332feswdfwedfsd'
];
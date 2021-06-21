<?php

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
        '/<controller>/<action>' => '<controller>/<action>'
    ],
    'cookieSalt' => 'sdfsdf2332feswdfwedfsd'
];
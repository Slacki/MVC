<?php

return [
    'applicationDirectory' => dirname(__FILE__) . '/App',
    'defaultAction' => 'site/index',
    'database' => [
        'dns' => 'mysql:host=localhost;dbname=yep;charset=UTF8',
        'username' => 'root',
        'password' => '',
        'options' => [], // leave it empty, dont delete the array
    ],
    'router' => [
        'rules' => require('routerRules.php'),
    ],
    'dispatcher' => [
        'appNamespace' => '\\TestApp',
    ]
];
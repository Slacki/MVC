<?php

return [
    [
        'pattern' => '<controller:[a-z]+>',
        'route' => '<controller>/index',
    ],
    [
        'pattern' => '<controller:[a-z]+>/<action:[a-z]+>',
        'route' => '<controller>/<action>',
    ],
    [
        'pattern' => '<controller:[a-z]+>/<action:[a-z]+>/<id:\d+>',
        'route' => '<controller>/<action>',
    ],
    [
        'pattern' => '<controller:[a-z]+>/<action:[a-z]+>/<id:\d+>/<param1:[a-z]+>/<param2:[a-zAZ0-9]+>/<param3:[a-e]+>',
        'route' => '<controller>/<action>',
    ],
    [
        'pattern' => 'posts',
        'route' => 'post/index',
    ],
    [
        'pattern' => 'post/<id:\d+>',
        'route' => 'post/view',
    ],
];
<?php

namespace Tests;

use Framework\Exception\HttpException;
use Framework\HttpRequest;
use Framework\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public $rules;

    public function setUp()
    {
        $this->rules = require(__DIR__ . '/routerRules.php');
    }

    public function testResolve()
    {
        $routes = [
            'site/index/123/someText/bR4ck3Ts/abcde',
            'site/index',
            'site/index/1337',
            'post/index',
            'post/123456',
        ];

        foreach ($routes as $r) {
            $_GET['q'] = $r;

            $request = new HttpRequest();
            $router = new Router(['rules' => $this->rules]);
            $result = $router->resolve($request);

            $this->assertInstanceOf('\\Framework\\HttpRequest', $result);
        }

        try {
            $_GET['q'] = 'si!@#!%/!#%%33';

            $request = new HttpRequest();
            $router = new Router(['rules' => $this->rules]);
            $result = $router->resolve($request);
        } catch (HttpException $e) {
            $this->assertInstanceOf('\\Framework\\Exception\\HttpException', $e);
        }

    }
}

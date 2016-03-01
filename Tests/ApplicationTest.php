<?php

namespace Tests;

use Framework\App;
use Framework\Exception\HttpException;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testExecuteAction()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['q'] = 'site/index';
        $app = new App(require(__DIR__ . '/applicationConfig.php'));
        $app->init();
    }

    public function testExecuteActionWithParameters()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['q'] = 'site/view/123';
        $app = new App(require(__DIR__ . '/applicationConfig.php'));
        $app->init();
    }

    public function testExecuteActionWithMissingParameter()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['q'] = 'site/view'; // missing id here
        $app = new App(require(__DIR__ . '/applicationConfig.php'));
        try {
            $app->init();
        } catch (HttpException $e) {
            $this->assertInstanceOf(HttpException::class, $e);
        }
    }

    public function testExecuteActionWithUnnecessaryParameter()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        // could be: site/view/1/asd
        // but param1 is optional
        $_GET['q'] = 'site/delete/1';
        $app = new App(require(__DIR__ . '/applicationConfig.php'));
        $app->init();
    }
}
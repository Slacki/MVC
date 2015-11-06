<?php

namespace Tests;

use Framework\App;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testExecuteActionWithParameters()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['q'] = 'site/index/12';
        $app = new App(require(__DIR__ . '/applicationConfig.php'));
        $app->init();
    }
}
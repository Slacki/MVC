<?php

namespace Tests;

use Framework\App;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public $config;

    public function setUp()
    {
        $this->config = [
            'defaultAction' => 'site/index',
            'router' => [
                'rules' => require('routerRules.php'),
            ],
        ];
    }

    public function testApplicationCreation()
    {
        $app = new App($this->config);
        $app->init();
        $this->assertInstanceOf('Framework\\App', $app);
    }

    public function testController()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $q = 'site/index';
        $_GET['q'] = $q;

        $app = new App($this->config);
        $app->init();
    }
}

<?php

namespace Framework;

use \PDO;

class Application
{
    public static $app;
    public $config;
    public $baseUrl;

    private $_db = null;

    public function __construct(array $config)
    {
        self::$app = $this;
        $this->config = new Config($config);
        $this->baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function init()
    {
        $request = new HttpRequest();
        $router = new Router($this->config->router);
        $request = $router->resolve($request);
        $dispatcher = new Dispatcher($request);
    }

    public function getDb()
    {
        if ($this->_db === null) {
            $this->_db = new PDO(
                $this->config->database['dns'],
                $this->config->database['username'],
                $this->config->database['password'],
                $this->config->database['options']
            );
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $this->_db;
    }
}
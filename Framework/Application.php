<?php

namespace Framework;

use \PDO;

class Application
{
    public static $app;
    public $config;
    public $baseUrl;

    private $_request;
    private $_db = null;

    public function __construct(array $config)
    {
        self::$app = $this;
        $this->config = new Config($config);
        isset($_SERVER['HTTP_HOST']) ? $this->baseUrl = $_SERVER['HTTP_HOST'] : $this->baseUrl = null;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $this->baseUrl = 'https://' . $this->baseUrl;
        } else {
            $this->baseUrl = 'http://' . $this->baseUrl;
        }
    }

    public function init()
    {
        $request = new HttpRequest();
        $router = new Router($this->config->router);
        $request = $router->resolve($request);
        $this->_request = $request;
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

    public function getRequest()
    {
        return $this->_request;
    }
}
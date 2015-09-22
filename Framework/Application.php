<?php

namespace Framework;

class Application
{
    public static $app;
    public $config;

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    public function init()
    {
        self::$app = $this;

        $request = new HttpRequest();
        $router = new Router($this->config->router);
        $router->resolve($request);
    }
}
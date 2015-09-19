<?php

namespace Framework;

class Bootstrap
{
    private $_config;

    public function __construct(array $config)
    {
        $this->_config = $config;
    }

    public function init()
    {
        $request = new HttpRequest();
        $router = new Router(); // pass a config here!
        $result = $router->resolve($request); // and somehow call this controller and action (dispatcher?)
    }
}
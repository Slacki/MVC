<?php

namespace Framework;

/**
 * Class HttpRequest
 * Represents the request to web server.
 *
 * @package Framework
 */
class HttpRequest
{
    /**
     * @var string GET parameter with request query.
     */
    public $query;

    /**
     * @var string controller/action route
     */
    public $route;

    /**
     * @var string method of request.
     */
    public $method;

    /**
     * @var string name of Controller coming from query
     */
    public $controller;

    /**
     * @var string name of Action coming from query
     */
    public $action;

    /**
     * @var array of parameters from query passed via GET request
     */
    public $parameters = [];

    /**
     * HttpRequest constructor.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query = isset($_GET['q']) ? $_GET['q'] : App::$app->config->defaultAction;
    }
}
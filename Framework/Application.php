<?php

namespace Framework;

use \PDO;

class Application
{
    /**
     * Application instance
     * @var Application
     */
    public static $app;

    /**
     * \Framework\Config object
     * @var Config
     */
    public $config;

    /**
     * @var string Base url of an application e.g. http://example.com
     */
    public $baseUrl;

    /**
     * @var \Framework\HttpRequest object
     */
    private $_request;

    /**
     * @var null|\PDO Database access object
     */
    private $_db = null;

    /**
     * Application constructor.
     * Doing all the stuff before an application can be initialized.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        // register error handler as early as possible
        $errorHandler = new ErrorHandler();
        $errorHandler->register();

        self::$app = $this;
        $this->config = new Config($config);
    }

    /**
     * Initializes an applicaton.
     *
     * @throws Exception\HttpException
     * @throws Exception\InvalidConfigException
     */
    public function init()
    {
        isset($_SERVER['HTTP_HOST']) ? $this->baseUrl = $_SERVER['HTTP_HOST'] : $this->baseUrl = null;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $this->baseUrl = 'https://' . $this->baseUrl;
        } else {
            $this->baseUrl = 'http://' . $this->baseUrl;
        }

        $request = new HttpRequest();
        $router = new Router($this->config->router);
        $request = $router->resolve($request);
        $this->_request = $request;
        $dispatcher = new Dispatcher($request);
        $dispatcher->dispatch();
    }

    /**
     * Opens connection with Database and returns Database access object
     *
     * @return null|PDO
     */
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

    /**
     * @return HttpRequest Application request object
     */
    public function getRequest()
    {
        return $this->_request;
    }
}
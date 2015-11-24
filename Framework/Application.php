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
        $router = new Router($this->config->router);
        $request = new HttpRequest();

        $this->_request = $router->resolve($request);
        $this->baseUrl = $this->_request->baseUrl;

        $dispatcher = new Dispatcher($this->_request);
        $dispatcher->dispatch();
    }

    /**
     * Opens connection with Database and returns Database access object
     *
     * @return null|PDO
     * @throws \Framework\ErrorException|\PDOException $e when connection with Database fails
     */
    public function getDb()
    {
        if ($this->_db === null) {
            try {
                $this->_db = new PDO(
                    $this->config->database['dns'],
                    $this->config->database['username'],
                    $this->config->database['password'],
                    $this->config->database['options']
                );
                $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                throw new ErrorException('Connection with database failed');
            }
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
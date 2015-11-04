<?php

namespace Framework;
use Framework\Exception\HttpException;

/**
 * Class Dispatcher calls controller and executes it's action specified in request
 *
 * @package Framework
 */
class Dispatcher
{
    public $defaultAppNamespace = '\\app';

    /**
     * @var HttpRequest Stores local instance of request to exctract data
     */
    private $_request;

    /**
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        $this->_request = $request;

        $this->_executeAction($this->_createController());
    }

    private function _createController()
    {
        $controllerWithNamespace = $this->defaultAppNamespace . '\\controllers\\' . $this->_request->controller;
        return new $controllerWithNamespace();
    }

    private function _executeAction($controller)
    {
        $actionName = 'action' . ucfirst($this->_request->action);
        if (!method_exists($controller, $actionName)) {
            throw new HttpException('Specified action not found.', 404);
        }
        call_user_func($this->_request->action, []);
    }
}
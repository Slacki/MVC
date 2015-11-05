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
    public $defaultAppNamespace = '\\App';

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
        $controllerWithNamespace = $this->defaultAppNamespace . '\\controllers\\' . ucfirst($this->_request->controller) . 'Controller';
        return new $controllerWithNamespace();
    }

    private function _executeAction($controller)
    {
        /* @var $controller Controller */

        $actionName = 'action' . ucfirst($this->_request->action);
        if (!method_exists($controller, $actionName)) {
            throw new HttpException('Specified action not found.', 404);
        }

        call_user_func([$controller, 'beforeAction']);
        call_user_func([$controller, $actionName]);
        call_user_func([$controller, 'afterAction']);
    }
}
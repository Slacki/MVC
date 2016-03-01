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
    public $appNamespace = '\\App';

    /**
     * @var HttpRequest Stores local instance of request to exctract data
     */
    private $_request;

    /**
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        if (isset(App::$app->config->dispatcher['appNamespace'])) {
            $this->appNamespace = App::$app->config->dispatcher['appNamespace'];
        }

        $this->_request = $request;
    }

    /**
     * Runs Dispatcher
     *
     * @throws HttpException
     */
    public function dispatch()
    {
        $this->_executeAction($this->_createController());
    }

    /**
     * Creates instance of Controller from Request
     *
     * @return \Framework\Controller
     * @throws HttpException
     */
    private function _createController()
    {
        $controllerWithNamespace = $this->appNamespace . '\\controllers\\' . ucfirst($this->_request->controller) . 'Controller';
        if (class_exists($controllerWithNamespace)) {
            return new $controllerWithNamespace();
        } else {
            throw new HttpException('Specified controller ' . $this->_request->controller . ' does not exist.', 404);
        }
    }

    /**
     * Executes controller's actoin based on request. Calls before and after action methods, too.
     * Parameters from Request are passed towards to method.
     *
     * @param $controller
     * @throws HttpException
     */
    private function _executeAction($controller)
    {
        /* @var $controller Controller */

        $actionName = 'action' . ucfirst($this->_request->action);
        if (!method_exists($controller, $actionName)) {
            throw new HttpException('Specified action ' . $actionName . ' not found.', 404);
        }

        $actionReflection = new \ReflectionMethod($controller, $actionName);
        $actionParameters = $actionReflection->getParameters();
        $parameters = [];
        foreach ($actionParameters as $ap) {
            if (isset($this->_request->parameters[$ap->name])) {
                $parameters[$ap->name] = $this->_request->parameters[$ap->name];
            } else if (!$ap->isDefaultValueAvailable()) {
                throw new HttpException('Bad request. Not all necessary parameters were passed.', 400);
            }
        }

        call_user_func([$controller, 'beforeAction']);
        call_user_func_array([$controller, $actionName], $parameters);
        call_user_func([$controller, 'afterAction']);
    }
}
<?php

namespace Framework;

/**
 * Class Router matches the request route with [[Rule]].
 * When matched [[Request]] object is being filled with parameters from route
 *
 * @package Framework
 */
class Router
{
    private $_rules = [];

    public function __construct($config = null)
    {
        if (isset($config['rules'])) {
            foreach ($config['rules'] as $rule) {
                $this->addRule($rule);
            }
        }
    }

    public function addRule($rule)
    {
        $this->_rules[] = new Rule($rule['pattern'], $rule['route']);
    }

    public function resolve(HttpRequest $request)
    {
        /* @var $correctRule \Framework\Rule */

        $query = $request->query;
        $correctRule = null;
        foreach ($this->_rules as $rule) {
            if (preg_match($rule->regex, $query)) {
                $correctRule = $rule;
                break;
            }
        }
        if ($correctRule === null) {
            throw new HttpException('Not found', 404);
        }

        // assign an parameter name to it's value coming from the request
        $attributesValues = explode('/', $query);

        $i = 0;
        foreach ($correctRule->parameters as $key => $val) {
            $request->parameters[$key] = $attributesValues[$i];
            $i++;
        }

        // resolve route (swap <sth> with the value from request)
        $route = explode('/', $correctRule->route);
        if (count($route) != 2) {
            throw new \Exception('Invalid route');
        }

        $routeFilledWithParameters = [];
        foreach ($route as $r) {
            if (preg_match('#^<(\w+)>$#', $r)) {
                $r = trim($r, '<>');
                $routeFilledWithParameters[] = $request->parameters[$r];
            } else {
                $routeFilledWithParameters[] = $r;
            }
        }

        $request->controller = $routeFilledWithParameters[0];
        $request->action = $routeFilledWithParameters[1];
        $request->route = implode('/', $routeFilledWithParameters);

        return $request;
    }
}
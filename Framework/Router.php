<?php

namespace Framework;

use Framework\Exception\HttpException;
use Framework\Exception\InvalidConfigException;

/**
 * Class Router matches the request route with [[Rule]].
 * When matched [[Request]] object is being filled with parameters from route.
 *
 * @package Framework
 */
class Router
{
    /**
     * @var array of [[Rule]] objects
     */
    private $_rules = [];

    /**
     * @param null $config Configuration array
     */
    public function __construct($config = null)
    {
        if (isset($config['rules'])) {
            foreach ($config['rules'] as $rule) {
                $this->addRule($rule);
            }
        }
    }

    /**
     * Creates new [[Rule]] object and stores it in an array.
     *
     * @param array $rule Rule configuration array
     */
    public function addRule($rule)
    {
        $this->_rules[] = new Rule($rule['pattern'], $rule['route']);
    }

    /**
     * Resolves the request.
     * Matches the request uri with the set of rules.
     * When the rule is found, then [[HttpRequest]] object is filled with parameters values coming from the request uri.
     * Otherwise an [[HttpException]] is thrown.
     *
     * It also figures out what the controller and action are.
     *
     * @param HttpRequest $request The incoming request
     * @return HttpRequest $request Filled with parameters from request uri
     * @throws HttpException When no rule is found and request cannot be resolved (404)
     * @throws InvalidConfigException When configuration is invalid
     */
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
            throw new InvalidConfigException('Invalid route');
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
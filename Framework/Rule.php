<?php

namespace Framework;

use Framework\Exception\InvalidConfigException;

/**
 * Class Rule represents rule used for routing request.
 *
 * @package Framework
 */
class Rule
{
    /**
     * @var string the pattern regex for [[Router]] to match
     */
    public $regex;

    /**
     * @var string the route to the controller->action()
     */
    public $route;

    /**
     * @var array parameters extracted from pattern
     */
    public $parameters = [];

    /**
     * @param string $pattern Pattern used for matching
     * @param string $route controller/action combination
     * @throws InvalidConfigException
     */
    public function __construct($pattern, $route)
    {
        $this->_compileRegex($this->_extractAttributes($pattern));
        $this->route = $route;
    }

    /**
     * Extracts attributes from pattern and stores them as array.
     * For example:
     * <controller:\w+>/<action:\w+>/<id:\d+>
     * is being converted to:
     * [
     *      'controller' => '\w+',
     *      'action' => '\w+',
     *      'id' => '\d+',
     * ]
     *
     * @param $pattern
     * @return array Of parameters [name => regex]
     * @throws InvalidConfigException When pattern is invalid
     */
    private function _extractAttributes($pattern)
    {
        $namedParameterRegex = '#^<([a-zA-Z0-9]+):(.+)>$#'; // <something:regex>
        $staticParameterRegex = '#^([a-zA-Z0-9]+)$#'; // string

        $groups = explode('/', $pattern);
        $attributes = [];
        foreach ($groups as $key => $val) {
            if (preg_match($namedParameterRegex, $val)) {
                $withoutBrackets = trim($val, '<>');
                $explode = explode(':', $withoutBrackets);
                $attributes[$explode[0]] = $explode[1];
                continue;
            }

            if (preg_match($staticParameterRegex, $val)) {
                $attributes[$val] = $val;
                continue;
            }

            throw new InvalidConfigException('The rule\'s pattern is invalid.');
        }
        $this->parameters = $attributes;

        return $this->parameters;
    }

    /**
     * Compiles regex from array of parameters.
     * For example:
     * [
     *      'controller' => '\w+',
     *      'action' => '\w+',
     *      'id' => '\d+',
     * ]
     * is being converted to:
     * #^(?P<controller>\w+)/(?P<action>\w+)/(?P<id>\d+)$#si
     *
     * @param array $attributes The attributes array
     */
    private function _compileRegex($attributes)
    {
        // groups thank's to Kadet1090's code
        $regex = '';
        foreach ($attributes as $name => $pattern) {
            $regex .= "/(?P<$name>$pattern)";
        }
        $regex = ltrim($regex, '/');
        $regex = '#^' . $regex . '$#si';

        $this->regex = $regex;
    }
}
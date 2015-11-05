<?php

namespace Framework;

use Framework\Exception\InvalidConfigException;

class Config
{
    private $_config = [];

    public function __construct(array $config)
    {
        $this->_config = $config;
    }

    public function __get($property)
    {
        if (array_key_exists($property, $this->_config)) {
            return $this->_config[$property];
        }
        throw new InvalidConfigException('Cannot find property: ' . $property);
    }

    public function __set($property, $value)
    {
        $this->_config[$property] = $value;
    }
}
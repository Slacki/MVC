<?php

namespace Framework;

use Framework\Exception\InvalidConfigException;

/**
 * Class Config
 * Allows to manage application configuration.
 *
 * @package Framework
 */
class Config
{
    /**
     * @var array $_config Stores the configuration array
     */
    private $_config = [];

    /**
     * Config constructor.
     * @param array $config Configuration array (usually provided as required file)
     */
    public function __construct(array $config)
    {
        $this->_config = $config;
    }

    /**
     * Retrieves data from config
     *
     * @param string $property Property name
     * @return mixed property
     * @throws InvalidConfigException
     */
    public function __get($property)
    {
        if (array_key_exists($property, $this->_config)) {
            return $this->_config[$property];
        }
        throw new InvalidConfigException('Cannot find property: ' . $property);
    }

    /**
     * Adds data to config
     *
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        $this->_config[$property] = $value;
    }
}
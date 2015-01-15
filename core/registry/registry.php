<?php

/**
 * Generic registry for managing global data.
 *
 * @author Mathias Verraes <mathias@delius.be>
 * @copyright 2010 delius bvba
 * @package one|content
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
class One_Registry
{
    /**
     * @var ArrayObject
     */
    private static $_data = null;

    /**
     * Constructor
     */
    private function __construct()
    {}

    /**
     * Initialize the ArrayObject
     */
    private static function _initialize()
    {
    	if(self::$_data === null) {
			self::$_data = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
    	}
    }

    /**
     * Get the value for a key
     *
     * @param string $key
     * @return mixed
     * @throws One_Exception if no entry is registered for $key
     */
    public static function get($key)
    {
        if (!self::has($key)) {
            throw new One_Exception("No entry is registered for key '$key'");
        }

        return self::$_data->offsetGet($key);
    }

    /**
     * Set a value for a key
     *
     * @param string $key The key in which to store the value
     * @param mixed $value The object to store
     */
    public static function set($key, $value)
    {
    	self::_initialize();
        self::$_data->offsetSet($key, $value);
    }

    /**
     * Check if the key exists in the registry
     *
     * @param  string $key
     * @return boolean
     */
    public static function has($key)
    {
    	self::_initialize();
        return self::$_data->offsetExists($key);
    }


}

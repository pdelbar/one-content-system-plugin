<?php
/**
 * One_Log instanciator class
 * @author traes
 */
class One_Log
{
	/**
	 * Constant for all severity levels
	 * @var int
	 */
	const ALL = 0;

	/**
	 * Constant for severity level "debug"
	 * @var int
	 */
	const DEBUG = 1;

	/**
	 * Constant for severity level "info"
	 * @var int
	 */
	const INFO = 2;

	/**
	 * Constant for severity level "warning"
	 * @var int
	 */
	const WARNING = 4;

	/**
	 * Constant for severity level "error"
	 * @var int
	 */
	const ERROR = 8;

	/**
	 * Constant for severity level "fatal"
	 * @var int
	 */
	const FATAL = 16;

	/**
	 * Constant for no severity levels
	 * @var int
	 */
	const NONE = 31;

	/**
	 * List of One_Log instances
	 * @var array
	 */
	protected static $_instances = array();

	/**
	 * Get a specific instance of One_Log
	 * @param string $type
	 * @param array $config
	 * @return One_Log_Abstract
	 */
	public static function getInstance($type, $config = array())
	{
		// Clean the requested type
		$requested = trim(strtolower($type));

		if(!isset(self::$_instances[$requested]))
		{
			// Check whether the requested class exists
			$logClass = 'One_Log_'.ucfirst($requested);
			if(false === class_exists($logClass)) {
				throw new One_Exception('The log-class "'.$logClass.'" does not exist');
			}

			// Create a new instance of the requested One_Log class
			$logger = new $logClass($config);

			// Make sure the class extends our abstract
			if(!$logger instanceof One_Log_Abstract) {
				throw new One_Exception('"'.$logClass.'" must extend One_Log_Abstract');
			}

			// Cache the logger for future use
			self::$_instances[$requested] = $logger;
		}

		return self::$_instances[$requested];
	}
}

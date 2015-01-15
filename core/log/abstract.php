<?php
/**
 * Abstract class for One_Log classes to extend from
 * @author traes
 */
abstract class One_Log_Abstract
{
	/**
	 * Configuration settings
	 * @var array
	 */
	protected $_config = array();

	/**
	 * Which severity levels need to be logged
	 * @var int
	 */
	protected $_logLevel = One_Log::ALL;

	/**
	 * List of registerd One_Log_Writer_Interfaces
	 * @var array
	 */
	protected $_writers = array();

	/**
	 * General constructor
	 * @param array $config Configuration settings
	 * @return One_Log_Abstract
	 */
	public function __construct(array $config = array())
	{
		$this->_config = array_change_key_case($config, CASE_LOWER);

		if(isset($this->_config['loglevel'])) {
			$this->_logLevel = intval($this->_config['loglevel']);
		}

		return $this;
	}

	/**
	 * Check whether the current level should be logged
	 * @param int $logLevel
	 * @return boolean
	 */
	public function logLevel($logLevel)
	{
		return $logLevel >= $this->_logLevel;
	}

	/**
	 * Set the levels that should be logged
	 * @param int $level
	 * @return One_Log
	 */
	public function setLogLevel($level)
	{
		$this->_logLevel = $level;
	}

	/**
	 * Return the currently registered writers
	 * @return array
	 */
	public function getWriters()
	{
		return $this->_writers;
	}

	/**
	 * Register a One_Log_Writer_Interface to the One_Log_Abstract
	 * @param One_Log_Writer_Interface $writer
	 * @return One_Log_Abstract
	 */
	public function registerWriter(One_Log_Writer_Interface $writer) {
		$this->_writers[] = $writer;

		return $this;
	}

	/**
	 * Register several One_Log_Writer_Interface to the One_Log_Abstract
	 * @param array $writers List of One_Log_Writer_Interfaces
	 * @return One_Log_Abstract
	 */
	public function registerWriters(array $writers) {

		foreach($writers as $writer) {
			if($writer instanceof One_Log_Writer_Interface) {
				$this->_writers[] = $writer;
			}
		}

		return $this;
	}

	/**
	 * Check whether the current log has writeable One_Log_Writer_Interfaces
	 * @return boolean
	 */
	public function isWriteable()
	{
		$writers = $this->getWriters();

		// If no One_Log_Writer_Interfaces are set, the file is not writeable
		if(0 == count($writers)) {
			return false;
		}

		// The One_Log_Abstract is not writable unless one of the registered One_Log_Writer_Interfaces is writeable
		$isWriteable = false;
		foreach($writers as $writer)
		{
			if($writer->isWriteable())
			{
				$isWriteable = true;
				break;
			}
		}

		return $isWriteable;
	}

	/**
	 * Write the actual log
	 * @param array $data
	 * @param int $level
	 * @return One_Log_Abstract
	 */
	protected function createLog(array $data = array(), $level = One_Log::INFO)
	{
		$writers = $this->getWriters();
		foreach($writers as $writer) {
			$writer->writeLog($data, $level);
		}

		return $this;
	}

	// Abstract functions

	/**
	 * Log the current data
	 * @param array $data
	 * @param int $level
	 * @return One_Log_Abstract
	 */
	public abstract function log(array $data = array(), $level = One_Log::INFO);

	/**
	 * Format the data to a proper form
	 * @param array $data
	 * @return array The formatted data
	 */
	protected abstract function formatData(array $data = array());
}
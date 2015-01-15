<?php
/**
 * Writer class for use with One_Log that uses a One_Scheme to write a log
 * @author traes
 *
 */
class One_Log_Writer_OneScheme implements One_Log_Writer_Interface
{
	/**
	 * One_Scheme to use
	 * @var One_Scheme
	 */
	protected $_scheme = NULL;

	/**
	 * Boolean whether the current writer is writeable or not
	 * @var boolean
	 */
	protected $_isWriteable = NULL;

	/**
	 * General constructor
	 * @param One_Scheme $scheme
	 * @return One_Log_Writer_OneScheme
	 */
	public function __construct(One_Scheme $scheme)
	{
		$this->_scheme = $scheme;

		// Check whether the scheme is writeable
		$this->isWriteable();

		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see One_Log_Writer_Interface::writeLog()
	 */
	public function writeLog(array $data = array(), $level = One_Log::INFO)
	{
		if(false === $this->isWriteable()) {
			return $this;
		}

		// Create the One_Model for the scheme
		$model = One_Repository::getInstance($this->_scheme->getName());

		// Set all data
		foreach($data as $key => $value) {
			$model->$key = $value;
		}

		// Set the severity level
		$model->level = $level;

		// Create the log
		$model->insert();

		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see One_Log_Writer_Interface::isWriteable()
	 */
	public function isWriteable()
	{
		// Only actually check if One_Log_Writer_OneScheme::_isWriteable is not set yet
		if(NULL === $this->_isWriteable)
		{
			try
			{
				// Try the see if the One_Scheme exists by trying to select a value with an "impossible" id
				$test = One_Repository::selectOne($this->_scheme->getName(), '-99999999999999999999999999999999999999999999999999');

				// If the query succeeded, the scheme exists
				$this->_isWriteable = true;
			}
			catch(Exception $e) { // If any exception is thrown, the One_Scheme is not writeable
				$this->_isWriteable = false;
			}
		}

		return $this->_isWriteable;
	}
}
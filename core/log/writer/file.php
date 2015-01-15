<?php
/**
 * Writer class for use with One_Log that writes the log to a file
 * @author traes
 *
 */
class One_Log_Writer_File implements One_Log_Writer_Interface
{
	/**
	 * Path to the file to write to
	 * @var string
	 */
	protected $_path = NULL;

	/**
	 * Boolean whether the current writer is writeable or not
	 * @var boolean
	 */
	protected $_isWriteable = NULL;

	/**
	 * General constructor
	 * @param string $path
	 * @return One_Log_Writer_File
	 */
	public function __construct($path)
	{
		$this->_path = $path;

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

		switch($level) {
			case One_Log::DEBUG:
				$level = 'DEBUG';
				break;
			case One_Log::INFO:
				$level = 'INFO';
				break;
			case One_Log::WARNING:
				$level = 'WARNING';
				break;
			case One_Log::ERROR:
				$level = 'ERROR';
				break;
			case One_Log::FATAL:
				$level = 'FATAL';
				break;
		}

		$ts = $data['ts'];
		unset($data['ts']);

		$message = $ts.' - '.$level.' - '.implode(' -- ', $data);

		$fh = fopen($this->_path, 'a');
		fwrite($fh, $message."\n");
		fclose($fh);

		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see One_Log_Writer_Interface::isWriteable()
	 */
	public function isWriteable()
	{
		// Only actually check if One_Log_Writer_File::_isWriteable is not set yet
		if(NULL === $this->_isWriteable)
		{
			$this->_isWriteable = false;

			// Check whether the configured path is writeable
			if(false !== ($fh = @fopen($this->_path, 'a')) && is_writeable($this->_path)) {
				$this->_isWriteable = true;
			}

			// Close the file if it's an actual pointer
			if(false !== $fh) {
				fclose($fh);
			}
		}

		return $this->_isWriteable;
	}
}
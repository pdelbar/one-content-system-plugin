<?php
/**
 * Writer class for use with One_Log that dumps the logmessage to the screen
 * @author traes
 *
 */
class One_Log_Writer_Vardump implements One_Log_Writer_Interface
{
	/**
	 * General constructor
	 * @return One_Log_Writer_Vardump
	 */
	public function __construct()
	{
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see One_Log_Writer_Interface::writeLog()
	 */
	public function writeLog(array $data = array(), $level = One_Log::INFO)
	{
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

		echo '<div class="One_Log_Dump">';
		echo '<h4 class="One_Log_Dump">== '.$level.' ==</h4>';
		echo '<span class="One_Log_Dump"><pre>';
		var_dump($data);
		echo '</pre>';
		echo '<h4 class="One_Log_Dump">========================</h4>';
		echo '</div>';
	}

	/**
	 * (non-PHPdoc)
	 * @see One_Log_Writer_Interface::isWriteable()
	 */
	public function isWriteable()
	{
		return true;
	}
}
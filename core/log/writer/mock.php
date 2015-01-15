<?php
/**
 * Writer class for use with One_Log that does nothing
 * @author traes
 *
 */
class One_Log_Writer_Mock implements One_Log_Writer_Interface
{
	/**
	 * General constructor
	 * @return One_Log_Writer_Mock
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
		return null;
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
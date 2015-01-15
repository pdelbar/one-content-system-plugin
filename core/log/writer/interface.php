<?php
interface One_Log_Writer_Interface
{
	/**
	 * Write the log
	 * @param array $data
	 * @param int $level
	 * @return One_Log_Writer_Interface
	 */
	public function writeLog(array $data = array(), $level = One_Log::INFO);

	/**
	 * Is the current writer writeable
	 */
	public function isWriteable();
}
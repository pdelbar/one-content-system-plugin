<?php
/**
 * One_Log class that handles logging specificly for regular messages
 * @author traes
 */
class One_Log_Message extends One_Log_Abstract
{
	/**
	 * Class constructor
	 * @param array $config Configuration settings
	 * @return One_Log_Abstract
	 */
	public function __construct(array $config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Log the current message
	 * The data must contain the following key:
	 * message
	 *
	 * @param array $data
	 * @param int $level
	 * @return One_Log_Message
	 */
	public function log(array $data = array(), $level = One_Log::INFO)
	{
		// if the current level shouldn't be logged, don't log it
		if(false === $this->logLevel($level)) {
			return $this;
		}

		// If no writers are set, register the mock writer
		if(0 == count($this->getWriters())) {
			$this->registerWriter(new One_Log_Writer_Mock());
		}

		// Check whether or not the log is writeable
		if(false === $this->isWriteable()) {
			throw new One_Exception('Could not write to the messagelog"');
		}

		// Format the data to the proper format
		$data = $this->formatData($data);

		// Create the log
		$this->createLog($data, $level);

		return $this;
	}

	/**
	 * Format the data to a proper form
	 * @param array $data
	 * @return array The formatted data
	 */
	protected function formatData(array $data = array())
	{
		$default = array(
			'message' => '',
		);

		// Lowercase all keys
		$data = array_change_key_case($data, CASE_LOWER);

		// Merge with the defaults
		$data = array_merge($default, $data);

		// Strip all invalid keys (maybe better with array_diff?)
		foreach($data as $key => $val) {
			if(false === in_array($key, array_keys($default))) {
				unset($data[$key]);
			}
		}

		// Remove all newlines from the message
		$data['message'] = preg_replace('/(\r\n|\n\r|\n|\r)/', ' ', $data['message']);

		// Set the current time
		$data['ts'] = date('Y-m-d H:i:s');

		return $data;
	}
}
<?php
/**
 * One_Log class that handles logging specificly for mails
 * @author traes
 */
class One_Log_Mail extends One_Log_Abstract
{
	/**
	 * Constant for "pending" status
	 * @var int
	 */
	const PENDING = 1;

	/**
	 * Constant for "sent" status
	 * @var int
	 */
	const SENT = 2;

	/**
	 * Constant for "failed" status
	 * @var int
	 */
	const FAILED = 4;

	/**
	 * Constant for when a wrong status is given
	 * @var int
	 */
	const WRONG_STATUS = 0;

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
	 * Log the current mail
	 * The data must contain some or all of the following keys:
	 * subject, from, to, cc, bcc, html, plain, charset, status
	 * Warning: The status must be a valid One_Log_Mail status ( One_Log_Mail::PENDING, One_Log_Mail::SENT or One_Log_Mail::FAILED )
	 *
	 * @param array $data
	 * @param int $level
	 * @return One_Log_Mail
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
			throw new One_Exception('Could not write to the maillog"');
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
			'subject' => '',
			'from' => '',
			'to' => '',
			'cc' => '',
			'bcc' => '',
			'html' => '',
			'plain' => '',
			'charset' => '',
			'status' => One_Log_Mail::WRONG_STATUS
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

		// format the data
		$data = $this->formatAddresses($data);
		$data = $this->formatStatus($data);

		// Set the current time
		$data['ts'] = date('Y-m-d H:i:s');

		return $data;
	}

	/**
	 * Format the addresses to a proper form
	 * @param array $data
	 * @return array The formatted data
	 */
	protected function formatAddresses(array $data = array())
	{
		foreach(array('from', 'to', 'cc', 'bcc') as $field)
		{
			if(is_array($data[$field])) {
				$data[$field] = implode(', ', $data[$field]);
			}
		}

		return $data;
	}

	/**
	 * Format the status
	 * @param array $data
	 * @return array The formatted data
	 */
	protected function formatStatus(array $data = array())
	{
		$data['status'] = intval($data['status']);

		// Check whether this is a proper status
		// WARNING: Actual status will be lost if no valid status is given
		// Reason for this is to maintain similarity between different applications
		// Sent can E.G. be 1, yes, sent, ...
		if(false === in_array($data['status'], array(One_Log_Mail::PENDING, One_Log_Mail::SENT, One_Log_Mail::FAILED))) {
			$data['status'] = One_Log_Mail::WRONG_STATUS;
		}

		return $data;
	}
}
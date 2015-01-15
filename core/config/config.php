<?php
class One_Config implements One_Config_Interface
{

	/**
	 * @var ArrayObject
	 */
	protected $_data;

	public function __construct(array $options = array())
	{
		$this->_data = new ArrayObject($options);
	}


	public function set($key, $value)
	{
		$this->_data[$key] = $value;
		return $this;
	}


	public function get($key, $default = null)
	{
		if(isset($this->_data[$key])) {
			return $this->_data[$key];
		}
		else {
			return $default;
		}
	}

	public function setDefaults(array $array)
	{
		foreach($array as $key => $value)
		{
			if(is_null($this->get($key))) {
				$this->set($key, $value);
			}
		}
		return $this;
	}

	public function toArray()
	{
		return $this->_data->getArrayCopy();
	}
}
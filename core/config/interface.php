<?php
interface One_Config_Interface
{
	public function __construct(array $array = array());

	public function set($key, $value);

	public function get($key, $default = null);

	public function setDefaults(array $array);

	public function toArray();
}
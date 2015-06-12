<?php
/**
 * Treats a scheme-attribute as an int
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage
ONEDISCLAIMER

 **/
class One_Type_Int extends One_Type
{
	/**
	 * Get the name of the attribute
	 *
	 * @return string
	 */
	public function getName()
	{
		return "int";
	}

	/**
	 * Returns the value as an int value
	 *
	 * @return int
	 */
	public function toString($value)
	{
		$value = preg_replace('!\%?([0-9\.\,]*)\%?!', '\1', $value);
		return intval($value);
	}
}

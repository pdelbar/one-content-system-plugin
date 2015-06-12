<?php
/**
 * Treats a scheme-attribute as a time
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Type
ONEDISCLAIMER

 **/
class One_Type_Time extends One_Type
{
	/**
	 * Get the name of the attribute
	 *
	 * @return string
	 */
	public function getName()
	{
		return "time";
	}

	/**
	 * Returns the value as a time value
	 *
	 * @return string
	 */
	public function toString($value)
	{
		return '"'.$value.'"';
	}
}

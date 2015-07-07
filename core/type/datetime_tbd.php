<?php
/**
 * Treats a scheme-attribute as a datetime
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Type
ONEDISCLAIMER

 **/
class One_Type_Datetime extends One_Type
{
	/**
	 * Get the name of the attribute
	 *
	 * @return string
	 */
	public function getName()
	{
		return "datetime";
	}

	/**
	 * Returns the value as a datetime value
	 *
	 * @return string
	 */
	public function toString($value)
	{
		return '"'.$value.'"';
	}
}

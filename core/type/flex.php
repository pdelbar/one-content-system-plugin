<?php
/**
 * Treats a scheme-attribute as a flex-string
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Type
ONEDISCLAIMER

 **/
class One_Type_Flex extends One_Type
{
	/**
	 * Get the name of the attribute
	 *
	 * @return string
	 */
	public function getName()
	{
		return "flex";
	}

	/**
	 * Returns the value as a string value
	 *
	 * @return string
	 */
	public function toString($value)
	{
		return '"'.$value.'"';
	}
}

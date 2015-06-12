<?php
/**
 * Treats a scheme-attribute as a decimal
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Type
ONEDISCLAIMER

 **/
class One_Type_Decimal extends One_Type
{
	/**
	 * Get the name of the attribute
	 *
	 * @return string
	 */
	public function getName()
	{
		return "decimal";
	}

	/**
	 * Returns the value as a float value
	 *
	 * @return float
	 */
	public function toString($value)
	{
		$value = preg_replace('!\%?([0-9\.\,]*)\%?!', '\1', $value);
		return floatval(str_replace(',', '.', $value));
	}
}

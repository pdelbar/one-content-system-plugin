<?php
/**
 * Treats a scheme-attribute as an int
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage
 * @filesource one/lib/type/int.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
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

<?php
/**
 * Treats a scheme-attribute as a datetime
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage Type
 * @filesource one/lib/type/datetime.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
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

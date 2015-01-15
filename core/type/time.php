<?php
/**
 * Treats a scheme-attribute as a time
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage Type
 * @filesource one/lib/type/time.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
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

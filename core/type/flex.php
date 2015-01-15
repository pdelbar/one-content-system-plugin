<?php
/**
 * Treats a scheme-attribute as a flex-string
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage Type
 * @filesource one/lib/type/flex.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
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

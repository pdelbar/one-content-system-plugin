<?php
/**
 * The One_Behavior abstract class implements the basic behavior for a mixin behavior
 * such as performing pre/postload actions.
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/behavior/behavior.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @abstract
 **/
abstract class One_Behavior
{
	/**
	 * Method that returns the name of the behavior
	 *
	 * @return string
	 * @abstract
	 */
	abstract public function getName();
}

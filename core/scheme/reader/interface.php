<?php
/**
 * One_Scheme_Reader Interface
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage Scheme
 * @filesource one/lib/scheme/ReaderInterface.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
interface One_Scheme_Reader_Interface
{
	/**
	 * Loads a scheme along with it's attributes, behaviors, relations, tasks, store and other information about the scheme
	 *
	 * @param $schemeName
	 * @return One_Scheme_Interface
	 */
	public static function load($schemeName);
}
<?php
/**
 * One_Scheme_Reader Interface
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Scheme
ONEDISCLAIMER

 **/
interface One_Scheme_Reader_Interface
{
	/**
	 * Loads a scheme along with it's attributes, behaviors, relations, tasks, store and other information about the scheme
	 *
	 * @param $schemeName
	 * @return One_Scheme
	 */
	public static function load($schemeName);
}
<?php
/**
 * One_Form_Reader Interface
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage Form
 **/
interface One_Form_Reader_Interface
{
	/**
	 * Loads a form definition
	 *
	 * @param $schemeName
	 * @param $formFile
	 * @return One_Form_Container_Form
	 */
	public static function load($schemeName, $formFile = 'form');
}
<?php
/**
 * This is basicly a Factory for getting instances of One_Store_Interface
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage Store
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Store
{
	/**
	 * Get a specified instance of One_Store_Interface
	 * @param string $type
	 * @throws One_Exception
	 * @return One_Store_Interface
	 */
	public static function getInstance($type)
	{
		$className = 'One_Store_'.ucfirst(strtolower($type));
		if(class_exists($className)) {
			$store = new $className($name);
			return $store;
		}
		else {
			throw new One_Exception('A store of type "'.$type.'" does not exist');
		}
	}
}

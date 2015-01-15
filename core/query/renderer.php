<?php
/**
 * Factory class for getting instances of One_Query_Renderer_Interface
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage Query
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @abstract
 **/
class One_Query_Renderer
{
	/**
	 * Get a specified instance of One_Store_Interface
	 * @param string $type
	 * @throws One_Exception
	 * @return One_Store_Interface
	 */
	public static function getInstance($type)
	{
		$className = 'One_Query_Renderer_'.ucfirst(strtolower($type));
		if(class_exists($className)) {
			$store = new $className($name);
			return $store;
		}
		else {
			throw new One_Exception('A query renderer of type "'.$type.'" does not exist');
		}
	}
}
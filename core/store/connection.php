<?php
/**
 * This is basicly a Factory for getting instances of One_Store_Connection_Interface
 * @author Corvus GloomMin
 *
 */
class One_Store_Connection
{
	/**
	 * Get an instance of a One_Store_Connection_Reader of a specific type
	 *
	 * @param $name Name of the connection, because you can have multiple connections of the same type
	 * @param $type Type of the connection EG: MySQL, PostGres, ... whatever a store/queryrenderer has been made for
	 */
	public static function getInstance($name, $type)
	{
		$className = 'One_Store_Connection_'.ucfirst(strtolower($type));
		if(class_exists($className)) {
			$connection = new $className($name);
			return $connection;
		}
		else {
			throw new One_Exception('A connection for type "'.$type.'" does not exist');
		}
	}
}
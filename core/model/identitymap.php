<?php

/**
 * Keeps track of One_Model instances and their id, to prevent loading the same
 * object twice
 *
 * @author Mathias Verraes <mathias@delius.be>
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/core/model.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
class One_Model_IdentityMap
{
	/**
	 * Add a model to the map
	 * @param One_Model_Interface $model
	 */
	public static function add(One_Model_Interface $model)
	{
		$scheme = $model->getSchemeName();
		$identity_name = $model->getIdentityName();
		$identity = $model->$identity_name;
		if(!$identity) {
			throw new One_Exception("The model has no identity yet and cannot be stored in the identity map.");
		}
		$key = "model.$scheme.$identity";
//    echo '<br>Added ', $key;
		One_Registry::set($key, $model);
	}

	/**
	 * Find a model for a scheme and id combo, false on fail
	 * @param string $schemeName Scheme name
	 * @param mixed $identity Identity
	 * @return One_Model_Interface
	 */
	public static function find($schemeName, $identity)
	{
		$key = "model.$schemeName.$identity";
//    echo '<br>Searching ', $key, (One_Registry::has($key) ? ' and found it' : ' but no luck');
//    if (One_Registry::has($key)) print_r( One_Registry::get($key));
		return One_Registry::has($key) ? One_Registry::get($key) : false;
	}

}
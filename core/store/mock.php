<?php
/**
 * Defines a mock One_Store
 *
 * @author traes
 * @copyright 2011 delius bvba
 * @package one|content
 * @subpackage Store
 **/
class One_Store_Mock implements One_Store_Interface
{
	/**
	 * Use the selectors to retrieve a number of objects.
	 *
	 * @param One_Scheme $scheme
	 * @param array $selectors
	 * @return array
	 */
	public function select(One_Scheme_Interface $scheme, array $selectors = array())
	{
		return array();
	}

	/**
	 * Select a single instance.
	 *
	 * @param One_Scheme $scheme
	 * @param mixed $identityValue
	 * @return One_Model
	 */
	public function selectOne( One_Scheme_Interface $scheme, $identityValue )
	{
		return null;
	}

	/**
	 * Run this scheme query and return the results
	 *
	 * @param One_Query $query
	 * @param boolean $asInstance
	 * @param boolean $overrideFilters
	 * @return array
	 */
	public function executeSchemeQuery( One_Query_Interface $query, $asInstance = true, $overrideFilters = false )
	{
		return array();
	}

	/**
	 * Return the number of results when the query is performed
	 *
	 * @param One_Query $query
	 * @param boolean $overrideFilters
	 * @return int
	 */
	public function executeSchemeCount( One_Query_Interface $query, $overrideFilters = false )
	{
		return 0;
	}

	/**
	 * Add a relationship to the model
	 *
	 * @param One_Model $model
	 * @param One_Link $link
	 */
	public function addRelations(One_Model_Interface $model, One_Link_Interface $link)
	{
		return null;
	}

	/**
	 * Save a relationship of the model
	 *
	 * @param One_Model $model
	 * @param One_Link $link
	 */
	public function saveRelations(One_Model_Interface $model, One_Link_Interface $link)
	{
		return null;
	}

	/**
	 * Delete a relationship from the model
	 *
	 * @param One_Model $model
	 * @param One_Link $link
	 */
	public function deleteRelations(One_Model_Interface $model, One_Link_Interface $link)
	{
		return null;
	}

	/**
	 * Insert a single instance
	 *
	 * @param One_Model $model
	 */
	public function insert( One_Model_Interface $model )
	{
		return null;
	}

	/**
	 * Update a single instance
	 *
	 * @param One_Model $model
	 */
	public function update( One_Model_Interface $model )
	{
		return null;
	}

	/**
	 * delete a single instance
	 *
	 * @param One_Model $model
	 * @return void
	 */
	public function delete( One_Model_Interface $model )
	{
		return null;
	}

	/**
	 * Function to set the proper encoding
	 * @param One_Scheme_Interface $scheme
	 * @param string $encoding (utf8, iso-8859-1, ...)
	 */
	public function setEncoding(One_Scheme_Interface $scheme, $encoding)
	{
		return null;
	}
}

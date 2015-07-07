<?php

die('deprecated stuff man');
/**
 * Defines how Joomla should be addressed to perform certain storage and retrieval actions
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Strategy
ONEDISCLAIMER

 **/
class One_Store_Joomla extends One_Store
{
	/**
	 * Return the proper Database-Object according to the scheme
	 *
	 * @param One_Scheme $scheme
	 * @return JDatabase
	 * @access protected
	 */
	protected function db(One_Scheme $scheme)
	{
		return $this->dbFromConnection($scheme->getConnection());
	}

	/**
	 * Return the proper Database-Object according to the connection
	 *
	 * @param One_Store_Connection_Interface $connection
	 * @return object
	 */
	protected function dbFromConnection(One_Store_Connection_Interface $connection)
	{
		return $connection->open();
	}

	/**
	 * Return the One_Renderer for this One_Store
	 *
	 * @return One_Renderer
	 */
	protected function getRenderer()
	{
 		// Must return a new instance every time as in rare cases some joins or selectfields or ...
 		// from a different query will be reused leading to unwanted queries
		return One_Query_Renderer::getInstance('mysql');
	}

	/**
	 * Perform a query and return the selection
	 *
	 * @param One_Query $query
	 * @return array
	 */
	public function doQuery( One_Query $query )
	{
		$scheme = $query->getScheme();
		$db = $this->db( $scheme );
		$renderer = $this->getRenderer();

		$sql = $renderer->render( $query );

		$db->setQuery($sql);
		$rows = $db->loadAssocList();

		$selection = array();
		if( is_array( $rows ) )
		{
			foreach($rows as $row) {
				$selection[] = $this->arrayToInstance( $scheme, $row );
			}
		}

		return $selection;
	}

	/**
	 * Use the selectors to retrieve a number of objects.
	 *
	 * @param One_Scheme $scheme
	 * @param array $selectors
	 * @return array
	 */
	public function select(One_Scheme $scheme, array $selectors = array())
	{
		$query = One_Repository::selectQuery( $scheme );

		if (count($selectors))
		{
			foreach ($selectors as $sel)
			{
				list( $path, $val, $op ) = $sel;
				$query->where( $path, $op, $val );
			}
		}

		$selection = $this->executeSchemeQuery( $query );

		return ( count( $selection ) > 0 ) ? $selection : NULL;
	}

	/**
	 * Convert an array to an instance of the specified scheme
	 *
	 * @param One_Scheme $scheme
	 * @param array $row
	 * @return One_Model
	 */
	protected function arrayToInstance(One_Scheme $scheme, $row)
	{
		// check the scheme cache
		$idAttribute = $scheme->getIdentityAttribute();
		$id = $row[$idAttribute->getName()];

		$cached = One_Model_IdentityMap::find( $scheme->getName(), $id );
		if ($cached) return $cached;

		// not found : create a new instance
		//TODO: use a specific class specified in the scheme
		$model = One::make( $scheme->getName() );

		// PD17OCT08: for optimal performance, raw-store the data row entirely
		$model->fromArray( $row );

		// fire afterLoad event for model
		$model->afterLoad();

		One_Model_IdentityMap::add($model);
		return $model;
	}

	/**
	 * Select a single instance.
	 *
	 * @param One_Scheme $scheme
	 * @param mixed $identityValue
	 * @return One_Model
	 */
	public function selectOne( One_Scheme $scheme, $identityValue )
	{
		$cached = One_Model_IdentityMap::find( $scheme->getName(), $identityValue );
		if ($cached) return $cached;

		$db = $this->db( $scheme );
		$renderer = $this->getRenderer();
		$query = One_Repository::selectQuery( $scheme );

		$idAttr = $scheme->getIdentityAttribute();
		$column = $idAttr->getName();
		$value = $idAttr->toString( $identityValue );

		$query->where( $column, 'eq', $value );

		$result = $this->executeSchemeQuery( $query );

		return ( count( $result ) > 0 ) ? $result[0] : NULL;
	}

	/**
	 * Run this scheme query and return the results
	 *
	 * @param One_Query $query
	 * @param boolean $asInstance
	 * @param boolean $overrideFilters
	 * @return array
	 */
	public function executeSchemeQuery( One_Query $query, $asInstance = true, $overrideFilters = false )
	{
		$scheme   =  $query->getScheme();
		$db       = $this->db( $scheme );
		$renderer = $this->getRenderer();
		$sql      =  $renderer->render( $query, $overrideFilters );

		$db->setQuery($sql);
		$rows = $db->loadAssocList();

		$selection = array();
		if( is_array( $rows ) )
		{
			$instanceScheme = $renderer->getInstanceScheme();
			foreach( $rows as $row )
			{
				if( $asInstance )
					$selection[] = $this->arrayToInstance( $instanceScheme, $row );
				else
				{
					$obj = new StdClass();
					foreach( $row as $key => $val )
					{
						$obj->$key = $val;
					}
					$selection[] =  $obj;
				}
			}
		}

		return $selection;
	}

	/**
	 * Return the number of results when the query is performed
	 *
	 * @param One_Query $query
	 * @param boolean $overrideFilters
	 * @return int
	 */
	public function executeSchemeCount( One_Query $query, $overrideFilters = false )
	{
		$scheme = $query->getScheme();
		$db = $this->db( $scheme );
		$renderer = $this->getRenderer();

		// Need to remember the old Select in case the One_Query is reused afterwards
		$oldSelect = $query->getSelect();

		$query->setSelect( array( 'COUNT( * ) AS total' ) );
		$sql = $renderer->render( $query, $overrideFilters );

		// put the old Select back into the One_Query
		$query->setSelect( $oldSelect );

		$db->setQuery($sql);

		return intval( $db->loadResult() );
	}

	/**
	 * Execute a raw Query
	 *
	 * @param string $sql
	 * @deprecated Can now be done by using a One_Query instance
	 */
	public function executeQuery( $sql )
	{
		throw new One_Exception_Deprecated('Use One_Query instead');
		$db = $this->dbFromConnection( $store );

		$db->setQuery($sql);
		$db->query();

		if( $db->ErrorMsg() )
		{
			throw new One_Exception( $db->errorMsg() );
		}
	}

	/**
	 * Lookup a single column from a table where a certain column has a certain value
	 *
	 * @param One_Store $store
	 * @param string $tableName
	 * @param string $lookupColumn
	 * @param string $retrieveColumn
	 * @param mixed $identityValue
	 * @return array
	 * @deprecated This can now be achieved with a One_Query object
	 */
	public function lookup( $store, $tableName, $lookupColumn, $retrieveColumn, $identityValue )
	{
		throw new One_Exception_Deprecated('Use One_Query instead');
		$db = $this->dbFromConnection( $store );

		// determine query
		$sql = "SELECT `" . $retrieveColumn . "` AS id FROM " . $tableName . " WHERE `" . $lookupColumn . "` = " . $db->Quote( $identityValue );

		// execute query
		$db->setQuery($sql);
		$rows = $db->loadAssocList();

		if( $db->getErrorNum() > 0 )
		{
			throw new One_Exception( $db->getErrorMsg() );
		}

		foreach ($rows as $row) {
			$selection[] = $row[ 'id' ];
		}

		return $selection;

	}

	/**
	 * Add a relationship to the model
	 *
	 * @param One_Model $model
	 * @param One_Link $link
	 */
	public function addRelations(One_Model $model, One_Link $link)
	{
		$added = $model->getAddedRelations();
		//print_r($added);
		if (isset($added[$link->getName()]))
		{
			// @todo - this probably isn't the correct way to get to the db object we need?
			// the db object should be based on the info in the $link, not the $model ...
			$scheme = One_Repository::getScheme( $model->getSchemeName() );
			$db = $this->db( $scheme );

			$table = $link->meta['table'];
			$localKey = $link->fk('local');
			$remoteKey = $link->fk('remote');

			$localId = $model->getIdentityName();
			$localValue = $model->$localId;

			// Insert the new (modified) relations in the given model
			$values = array();
			foreach($added[$link->getName()] as $remoteValue)
			{
				if( is_array( $remoteValue ) )
				{
					foreach( $remoteValue as $rVal )
					{
						$values[] = '( "' . mysql_real_escape_string( $localValue ) . '", "' . mysql_real_escape_string( $rVal ) . '") ';
					}
				}
				else
					$values[] = '( "' . mysql_real_escape_string( $localValue ) . '", "' . mysql_real_escape_string( $remoteValue) . '") ';
			}

			// only run the insert query if we actually received new values
			if (count($values))
			{
				$sql = 'INSERT INTO	`' . $table . '`	(`' . $localKey . '`, `' . $remoteKey . '`) ' .
						'VALUES '. implode( ", ", $values );

				$db->execute($sql);
			}
		}
	}

	/**
	 * Save a relationship of the model
	 *
	 * @param One_Model $model
	 * @param One_Link $link
	 */
	public function saveRelations(One_Model $model, One_Link $link)
	{
		$modified = $model->getDeltaRelations();

		if (isset($modified[$link->getName()]))
		{
			$linkConnection = One_Repository::getConnection($link->meta['connection']);

			if(!($linkConnection->getStore() instanceof One_Store_Joomla)) {
				$linkConnection->getStore()->saveRelations($model, $link);
			}
			else {
				$db = $this->dbFromConnection($linkConnection);

				$table = $link->meta['table'];
				$localKey = $link->fk('local');
				$remoteKey = $link->fk('remote');

				$localId = $model->getIdentityName();
				$localValue = $model->$localId;

				// Start by removing the old relations between these models
				$sql = 'DELETE FROM `' . $table . '` ' .
						'WHERE `' . $localKey . '` = "' . $localValue . '"';

				$db->execute($sql);

				// Insert the new (modified) relations in the given model
				$values = array();
				foreach($modified[$link->getName()] as $remoteValue)
					$values[] = '("' . $localValue . '", "' . $remoteValue . '")';

				// only run the insert query if we actually received new values
				if( count( $values ) > 0 )
				{
					$sql = 'INSERT INTO `' . $table . '` (`' . $localKey . '`, `' . $remoteKey . '`) ' .
							'VALUES ' . implode( ', ', $values );

					$db->execute($sql);
				}
			}
		}
	}

	/**
	 * Delete a relationship from the model
	 *
	 * @param One_Model $model
	 * @param One_Link $link
	 */
	public function deleteRelations(One_Model $model, One_Link $link)
	{
		$deleted = $model->getDeletedRelations();

		if (isset($deleted[$link->getName()]))
		{
			// @todo - this probably isn't the correct way to get to the db object we need?
			// the db object should be based on the info in the $link, not the $model ...
			$scheme = One_Repository::getScheme( $model->getSchemeName() );
			$db = $this->db( $scheme );

			$table = $link->meta['table'];
			$localKey = $link->fk('local');
			$remoteKey = $link->fk('remote');

			$localId = $model->getIdentityName();
			$localValue = $model->$localId;

			// Insert the new (modified) relations in the given model
			$values = array();
			foreach($deleted[$link->getName()] as $remoteValue)
			{
				if( is_array( $remoteValue ) )
				{
					foreach( $remoteValue as $rVal )
					{
						$values[] = '`' . $remoteKey . '` = "' . mysql_real_escape_string( $rVal, $db ) . '"';
					}
				}
				else
					$values[] = '`' . $remoteKey . '` = "' . mysql_real_escape_string( $remoteValue, $db ) . '"';
			}

			// only run the insert query if we actually received new values
			if (count($values))
			{
				$sql = 'DELETE FROM `' . $table . '` ' .
					'WHERE `' . $localKey . '` = "' . mysql_real_escape_string( $localValue, $db ) . '"' .
					'AND ( ' . implode( ' OR ', $values ) . ' )';

				$db->execute($sql);
			}
		}
	}

	/**
	 * Insert a single instance
	 *
	 * @param One_Model $model
	 */
	public function insert( One_Model $model )
	{
		$scheme = One_Repository::getScheme( $model->getSchemeName() );
		$db = $this->db( $scheme );

		// determine table to insert into
		$table = $this->getTable( $scheme );

		$idSet = false;

		$idAttr = $scheme->getIdentityAttribute();
		$data = new stdClass();

		foreach($scheme->getAttributes() as $attribute)
		{
			$attName = $attribute->getName();
			// if the model's identity attribute is set (probably to zero for new items),
			// we need to skip it when inserting .
			// @todo: should only be the case for auto increment id's, we
			// ought to allow preset values for id fields which don't auto increment...

			if($attName <> $idAttr->getName())
			{
				if( isset( $attName ) )
					$data->$attName  = $model->$attName;
				else
					$data->$attName  = '';
			}
			else
			{
				if( !is_null( $model[$attName] ) && trim( $model[$attName] ) != '0' && trim( $model[$attName] ) != '' )
				{
					$idSet = $model[$attName];
					$data->$attName = $model[$attName];
				}
			}
		}

		$modified = $model->getModified();
		foreach ($scheme->getLinks() as $link)
		{
			if ($link->getLinkType() == "manytoone")
			{
				$fk = $link->fk();
				if (isset( $modified[ $fk ]))
				{
					$data->$fk = $modified[ $fk ];
				}
			}
		}

		if(class_exists('JPluginHelper', false) && JPluginHelper::isEnabled('system', 'nooku'))
		{
			$data = $this->objectToArray($data);
			$conf = new JConfig();
			$db->insert(preg_replace(array('/^'.$conf->dbprefix.'/', '/#__/'), '', $table), $data, true);
		}
		else
		{
			if($idSet !== false) {
				$db->insertObject($table, $data, $idAttr->getName());
			}
			else {
				$db->insertObject($table, $data);
			}
		}


		if( trim( $db->errorMsg() ) != '' )
			throw new One_Exception( $db->errorMsg() );

		if( $idSet !== false )
			$newId = $idSet;
		else
			$newId = $db->insertid();

		if ($newId)
		{
			$idfield = $idAttr->getName();
			$model->$idfield = $newId;

			$modifiedRelations = $model->getDeltaRelations();

			// Handle ManyToMany relations
			foreach ($scheme->getLinks() as $link)
			{
				if ($link->getLinkType() == "manytomany")
				{
					if (isset( $modifiedRelations[ $link->getName() ]))
					{
						$model->saveRelated($link);
					}
				}
			}
		}

		return null;
	}

	/**
	 * Update a single instance
	 *
	 * @param One_Model $model
	 */
	public function update( One_Model $model )
	{
		$scheme = One_Repository::getScheme( $model->getSchemeName() );
		$db = $this->db( $scheme );

		// determine table to insert into
		$table = $this->getTable( $scheme );

		//create clauses
		$modified = $model->getModified();
		$modifiedRelations = $model->getDeltaRelations();

		$data = new stdClass();

		foreach ($scheme->getAttributes() as $attName => $at ) {
			if (isset( $modified[ $attName ]))
			{
        $colName = $at->getColumn();
				$data->$colName = $modified[ $attName ];
//				$data->$attName = $modified[ $attName ];
			}
		}

		// Check for relationships (FK values), cannot use attribute but must use column or link name
		// JL 06JAN2008 - Three possible situations, two are needed:
		// * ManyToOne
		//		* The FK is a field in the model's record
		//		* We need to set this field BEFORE saving the record
		// * ManyToMany
		//		* Relations are in a separate table
		//		* We should set them AFTER saving the record (especially when inserting a new record)
		// * OneToMany
		// 		* Not needed for now - When editing, we'll usually edit the child and select it's parent
		foreach ($scheme->getLinks() as $link)
		{
			if ($link->getLinkType() == "manytoone")
			{
				$fk = $link->fk();
				if (isset( $modified[ $fk ]))
				{
					$data->$fk = $modified[ $fk ];
				}
			}
		}

		$idAttr = $scheme->getIdentityAttribute();
		$id = $idAttr->getName();
		$value = $model->$id;
		$value = $idAttr->toString( $value );

		// TR20120203 commented this section as Joomla doesn't parse a quoted string wel either
// 		if(class_exists('JPluginHelper', false) && JPluginHelper::isEnabled('system', 'nooku'))
// 		{
			if($idAttr->getType() instanceof One_Type_String) {
				$value = preg_replace('/^\"(.*)\"$/i', '$1', $value);
			}
// 		}

		$data->$id = $value;

		$nrChanged = 0;
		foreach( $data as $key => $val )
		{
			if( $key != $id ) {
				$nrChanged++;
			}
		}

		if($nrChanged > 0)
		{
			if(class_exists('JPluginHelper', false) && JPluginHelper::isEnabled('system', 'nooku'))
			{
				$conf = new JConfig();
				$db->updateObject(preg_replace(array('/^'.$conf->dbprefix.'/', '/#__/'), '', $table), $data, $id);
			}
			else {
				$db->updateObject($table, $data, $id);
			}
		}

		if(trim($db->errorMsg()) != '') {
			throw new One_Exception($db->errorMsg());
		}

		// Handle ManyToMany relations
		foreach ($scheme->getLinks() as $link)
		{
			if ($link->getLinkType() == "manytomany")
			{
				if (isset( $modifiedRelations[ $link->getName() ]))
				{
					$model->saveRelated($link);
				}
			}
		}

		return null;
	}

	/**
	 * delete a single instance
	 *
	 * @param One_Model $model
	 * @return void
	 */
	public function delete( One_Model $model )
	{
		$scheme = One_Repository::getScheme( $model->getSchemeName() );
		$db = $this->db( $scheme );

		// determine table to insert into
		$table = $this->getTable( $scheme );

		$sql = 'DELETE FROM '.$table.' ';

		$idAttr = $scheme->getIdentityAttribute();
		$id = $idAttr->getName();
		$value = $model->$id;
		$value = $idAttr->toString( $value );

		$where = 'WHERE `' . $id . '` = ' . $value;

		if(class_exists('JPluginHelper', false) && JPluginHelper::isEnabled('system', 'nooku')) {
			$db->delete(preg_replace(array('/^'.$conf->dbprefix.'/', '/#__/'), '', $table), $where);
		}
		else {
			$db->execute($sql.$where);
		}

		return null;
	}

	/**
	 * Set the attributes and values to be usable in a query
	 *
	 * @param One_Scheme _Attribute $attribute
	 * @param mixed $value
	 * @return String
	 * @deprecated This is now done by the One_Renderer
	 */
	protected function setToSQL( &$attribute, $value )
	{
		throw new One_Exception_Deprecated('Use One_Renderer instead');
		return '`' . $attribute->getName() . '` = ' . $attribute->toString( $value );
	}

	/**
	 * Get the mysql table used for the scheme
	 *
	 * @param One_Scheme $scheme
	 * @return string
	 */
	public function getDatasource( One_Scheme $scheme )
	{
		$resources = $scheme->getResources();
		$source = $resources['table'];

		return $source;
	}

	/**
	 * Get the current Joomla user
	 *
	 * @return JUser
	 */
	public function getCurrentUser()
	{
		$currUser = new StdClass();
		$currUser->id      = 0;
		$currUser->name    = 'guest';
		$currUser->isAdmin = false;

		$user = JFactory::getUser();
		if( $user->guest != 1 )
		{
			$currUser->id      = $user->id;
			$currUser->name    = $user->name;
			$currUser->isAdmin = in_array( $user->gid, array( 23, 24, 25 ) ) ;
		}

		return $currUser;
	}

	/**
	 * Get the table used for the scheme
	 * @param One_Scheme $scheme
	 * @return string Table name used for the scheme
	 */
	protected function getTable(One_Scheme $scheme)
	{
		$resources = $scheme->getResources();
		if(isset($resources['table'])) {
			return $resources['table'];
		}
		else {
			throw new One_Exception('A table must be defined for the scheme "'.$scheme->getName().'"');
		}
	}

	/**
	 * Helper function to turn an object into an array (needed when nooku is being used)
	 */
	protected function objectToArray( $object )
    {
        if( !is_object( $object ) && !is_array( $object ) )
        {
            return $object;
        }
        if( is_object( $object ) )
        {
            $object = get_object_vars( $object );
        }

        return array_map( array($this, 'objectToArray'), $object );
    }

	/**
	 * Function to set the proper encoding
	 * @param One_Scheme $scheme
	 * @param string $encoding (utf8, iso-8859-1, ...)
	 */
	public function setEncoding(One_Scheme $scheme, $encoding)
	{
		$db  = $this->db( $scheme );
		$sql = 'set names "'.mysql_real_escape_string($encoding).'"';

		$db->setQuery($sql);
		$db->query();
	}
}

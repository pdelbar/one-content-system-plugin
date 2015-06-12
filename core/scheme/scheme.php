<?php
/**
 * The One_Scheme class contains all meta-information about the One_Model.
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Scheme
ONEDISCLAIMER

 **/
class One_Scheme
{
	/**
	 * @var string Added descriptive info
	 */
	public $title;

	/**
	 * @var string Added descriptive info
	 */
	public $description;

	/**
	 * @var string Added image icon
	 */
	public $image;

	/**
	 * @var string Group name
	 */
	public $group = 'default';

	/**
	 * @var int Group order
	 */
	public $grouporder = 0;

	/**
	 * @var array Added for more flexible options management
	 */
	public $information = array();

	/**
	 * @var string $_name The name of the scheme
	 */
	protected $_name;

	/**
	 * @var array $_resource Scheme-store information to know the specific location of data (table, module)
	 * @TODO I think this should be moved to an instance of a store? Yes it should
	 */
	protected $_resource = array();

	/**
	 * @var array $_attributes This is an array (attributeName => attribute) of One_Scheme_Attribute instances
	 */
	protected $_attributes = array();

	/**
	 * @var array $_aliasAttributes This is an array (alias => attribute) used by the model to understand aliases used instead of former 'column'
	 */
	protected $_aliasAttributes = array();

	/**
	 * @var array $_links An array of One_Link instances
	 */
	protected $_links = array();

	/**
	 * @var array $_linksById An array of One_Link instances
	 */
	protected $_linksById = array();

	/**
	 * @var array $_foreignKeys An array of foreign keys being used in this scheme
	 */
	protected $_foreignKeys = array();

	/**
	 * @var One_Store_Connection_Interface A reference to the One_Store in which the model sits
	 */
	protected $_connection;

	/**
	 * @var array $_meta Maintained for the short term
	 */
	protected $_meta = array();

	/**
	 * @var array Array of behaviors
	 */
	protected $_behaviors = array();

	/**
	 * @var array Options for each behavior, if needed (behaviorname => array)
	 */
	protected $_behaviorOptions = array();

	/**
	 * @var array Array of permissions for this scheme
	 */
	protected $_rules = array();

	/**
	 * @var string Type of the scheme (is important for subschemes to determine whether they're saved as xml/json/yaml/...
	 */
	protected $_type = 'xml';

	/**
	 * @var array List of subschemes
	 */
	protected $_subschemes = array();

	/**
	 * Class constructor
	 *
	 * @param string $schemeName
	 */
	public function __construct($schemeName)
	{
		$this->_name = $schemeName;
	}

	/**
	 * Returns the name of the scheme
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * Returns the meta-information of the scheme
	 * @todo rename to getMeta
	 * @return array
	 */
	public function getMeta()
	{
		return $this->_meta;
	}

	/**
	 * Set the meta-data for the scheme
	 *
	 * @param array $meta
	 */
	public function setMeta($meta)
	{
		$this->_meta = $meta;
	}

	/**
	 * Gets the connection for the scheme
	 *
	 * @return One_Store_Connection_Interface
	 */
	public function getConnection()
	{
		return $this->_connection;
	}

	/**
	 * Sets the storeconnection of the scheme
	 *
	 * @param One_Store_Connection_Interface $connection
	 */
	public function setConnection(One_Store_Connection_Interface $connection)
	{
		$this->_connection = $connection;
	}

	/**
	 * Gets the store for the scheme
	 * @return One_Store
	 */
	public function getStore()
	{
		$store = $this->_connection->getStore();
		return $store;
	}

	//-------------------------------------------------------------------------------
	// ATTRIBUTES
	//-------------------------------------------------------------------------------

	/**
	 * Get all attributes from the scheme
	 * @todo rename to getAttributes
	 * @return array List of One_Scheme_Attributes
	 */
	public function getAttributes()
	{
		return $this->_attributes;
	}

	/**
	 * Set all attributes for the scheme
	 *
	 * @param array $attributes
	 */
	public function setAttributes($attributes)
	{
		$this->_attributes = $attributes;
	}

	/**
	 * Set alias-attributes for the scheme
	 *
	 * @param array $attributes
	 */
	public function setAliasAttributes($attributes)
	{
		$this->_aliasAttributes = $attributes;
	}

	/**
	 * Add an attribute to the scheme
	 *
	 * @param One_Scheme_Attribute $attribute
	 */
	public function addAttribute(One_Scheme_Attribute $attribute)
	{
		$this->_attributes[$attribute->getName()] = $attribute;
	}

	/**
	 * Retrieve attribute based on name OR alias
	 *
	 * @param string $nameOrAlias
	 * @return One_Scheme_Attribute
	 */
	public function getAttribute($nameOrAlias)
	{
		if(isset($this->_attributes[$nameOrAlias])) {
			return $this->_attributes[$nameOrAlias];
		}
		if(isset($this->_aliasAttributes[$nameOrAlias])) {
			return $this->_aliasAttributes[$nameOrAlias];
		}

		return NULL; // This should not throw an exception (EG: flex fields will never be found here)
//		throw new One_Exception('Attribute not found');
	}

	/**
	 * Retrieves whether the scheme has a certain attribute
	 *
	 * @param string $nameOrAlias
	 * @return boolean
	 */
	public function hasAttribute($nameOrAlias)
	{
		return null !== $this->getAttribute($nameOrAlias);
	}

	/**
	 * Get all links from the scheme
	 *
	 * @return array
	 */
	public function getLinks()
	{
		return $this->_linksById;
	}

	/**
	 * Get a specific link
	 *
	 * @param string $roleName
	 * @return One_Link
	 */
	public function getLink($roleName)
	{
		return (isset($this->_links[$roleName])) ? $this->_links[$roleName] : NULL;
	}

	/**
	 * Add a link to the scheme
	 *
	 * @param One_Link $link
	 */
	public function addLink(One_Link $link)
	{
		$this->_links[$link->getName()] = $link;
		$this->_linksById[$link->getLinkId()] = $link;

		if($link->getLinkType() instanceof One_Link_Type_Manytoone)
		{
			if(null !== $link->getForeignKey()) {
				$this->_foreignKeys[] = $link->getForeignKey();
			}
		}
	}

	/**
	 * Return the list of all local foreign keys that are present in the One_Scheme
	 *
	 * @return array List of local foreign keys
	 */
	public function getForeignKeys()
	{
		return $this->_foreignKeys;
	}

	/**
	 * Return all roles in the scheme
	 * @return array
	 */
	public function getRoles()
	{
		return $this->_links;
	}

	/**
	 * Get all behaviors in the scheme
	 * @return array
	 */
	public function getBehaviors()
	{
		// Make sure flex behavior is always performed last
		$flex = null;
		$tmp = array();
		foreach($this->_behaviors as $key => $behavior)
		{
			if($behavior instanceof One_Behavior_Scheme_Flex)
			{
				$flex = $behavior;
				unset($this->_behaviors[$key]);
			}
		}

		if(null !== $flex) {
			$this->_behaviors[] = $flex;
		}

		return $this->_behaviors;
	}

	/**
	 * Add a behavior to the scheme
	 *
	 * @param One_Behavior $behavior
	 * @param array $options
	 */
	public function addBehavior(One_Behavior $behavior, $options = array())
	{
		$this->_behaviors[] = $behavior;
		$this->_behaviorOptions[strtolower($behavior->getName())] = $options;
	}


  public function getBehavior($behaviorName)
  {
    return (isset($this->_behaviors[strtolower($behaviorName)])) ? $this->_behaviors[strtolower($behaviorName)] : NULL;
  }

	/**
	 * Get the behavior options of a specified behavior
	 *
	 * @param string $behaviorName
	 * @return array
	 */
	public function getBehaviorOptions($behaviorName)
	{
		return (isset($this->_behaviorOptions[strtolower($behaviorName)])) ? $this->_behaviorOptions[strtolower($behaviorName)] : NULL;
	}


	/**
	 * Check whether or not the scheme has a specified behavior
	 *
	 * @param string $behaviorName
	 * @return boolean
	 */
	public function hasBehavior($behaviorName)
	{
		foreach($this->getBehaviors() as $behavior) {
			$possibleNames = array(
									'One_Behavior_Scheme_'.ucfirst($behaviorName),
									'One_Behavior_Scheme_'.ucfirst($this->getName()).'_'.ucfirst($behaviorName)
								);
			if(in_array(get_class($behavior), $possibleNames)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get all rules in the scheme for a specific task
	 * @todo rename to getRules
	 * @param string $taskName
	 * @return array
	 */
	public function getRules($taskName)
	{
		return (isset($this->_rules[$taskName])) ? $this->_rules[$taskName] : NULL;
	}

	/**
	 * Add a rule to the scheme for a specific task
	 *
	 * @param string $taskName
	 * @param One_Permission_Rule $rule
	 */
	public function addRule($taskName, One_Permission_Rule $rule)
	{
		if(!isset($this->_rules[$taskName])) {
			$this->_rules[$taskName] = array();
		}

		$this->_rules[$taskName][] = $rule;
	}




	/**
	 * Get the identity attribute of this scheme
	 * @return One_Scheme_Attribute
	 */
	public function getIdentityAttribute()
	{
		$atts = array();

		foreach($this->getAttributes() as $attr)
		{
			if($attr->isIdentity()) {
				return $attr;
			}
		}
		return NULL;
	}

	/**
	 * Get the keyvalue attribute of this scheme.
	 * If no keyvalue-attribute is indicated, return the identity-attribute.
	 * @return One_Scheme_Attribute
	 */
	public function getKeyvalueAttribute()
	{
		$atts = array();

		foreach ($this->getAttributes() as $attr) {
			if($attr->isKeyValue()) {
				return $attr;
			}
		}

		return $this->getIdentityAttribute();
	}


	/**
	 * Update the model
	 *
	 * @param One_Model $model
	 * @return mixed
	 * @TODO Should CRUD be here?
	 */
	public function update(One_Model $model)
	{
		$store = $this->getStore();
		return $store->update($model);
	}

	/**
	 * Insert the model
	 *
	 * @param One_Model $model
	 * @return mixed
	 * @TODO Should CRUD be here?
	 */
	public function insert(One_Model $model)
	{
		$store = $this->getStore();
		return $store->insert($model);
	}

	/**
	 * Delete the model
	 *
	 * @param One_Model $model
	 * @return mixed
	 * @TODO Should CRUD be here?
	 */
	public function delete(One_Model $model)
	{
		$store = $this->getStore();
		return $store->delete($model);
	}

	/**
	 * Set the resources of the scheme
	 *
	 * @param array $options
	 */
	public function setResources(array $options)
	{
		$this->_resource = $options;
	}

	/**
	 * Get the resources of the scheme
	 *
	 * @return array
	 */
	public function getResources()
	{
		return $this->_resource;
	}

	/**
	 * Get the scheme's datasource
	 * @todo check if this is actually used
	 * @return array
	 */
	public function getDatasource()
	{
		throw new One_Exception_NotImplemented('Is this used?');
		return $this->strategy()->getDatasource( $this );
	}

	/**
	 * Get the scheme's title.
	 * If the scheme does not have a title, return the scheme's name
	 * @return string
	 */
	public function getTitle()
	{
		if($this->title) {
			return $this->title;
		}
		return $this->_name;
	}

	/**
	 * Does the scheme have subschemes?
	 * Subschemes are grouped attributes in the scheme itself, relation is always 1*n
	 * @return boolean
	 */
	public function hasSubschemes()
	{
		return (count($this->_subschemes) > 0);
	}

	/**
	 * Get all subschemes of the scheme
	 * @return array List of subschemes
	 */
	public function getSubschemes()
	{
		return $this->_subschemes;
	}

	/**
	 * Set a single subscheme
	 * @param string $name name of the Subscheme
	 * @param One_Scheme $scheme
	 */
	public function setSubscheme($name, One_Scheme $scheme)
	{
		$this->_subschemes[$name] = $scheme;
	}

	/**
	 * Get a particular subscheme
	 * @param string $name
	 * @return One_Scheme
	 */
	public function getSubscheme($name)
	{
		return (isset($this->_subschemes[$name])) ? $this->_subschemes[$name] : NULL;
	}

	/**
	 * Set the type of the current scheme
	 * @param $type xml, json (need to know so we know how to parse the field)
	 */
	public function setType($type)
	{
		$this->_type = $type;
	}

	/**
	 * Get the type of the current scheme
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}


	//-------------------------------------------------------------------------------
	// All @deprecated shit. Delete when you feel the time is ripe
	//-------------------------------------------------------------------------------

	/**
	 * @deprecated
	 */
	public function links()
	{
		throw new One_Exception_Deprecated("use get".ucfirst(__FUNCTION__)."() instead");
	}
	/**
	 * @deprecated
	 */
	public function title()
	{
		throw new One_Exception_Deprecated("use get".ucfirst(__FUNCTION__)."() instead");
	}
	/**
	 * @deprecated
	 */
	public function meta()
	{
		throw new One_Exception_Deprecated("use get".ucfirst(__FUNCTION__)."() instead");
	}
	/**
	 * @deprecated
	 */
	public function name()
	{
		throw new One_Exception_Deprecated("use get".ucfirst(__FUNCTION__)."() instead");
	}
	/**
	 * @deprecated
	 */
	public function roles()
	{
		throw new One_Exception_Deprecated("use get".ucfirst(__FUNCTION__)."() instead");
	}
	/**
	 * @deprecated
	 */
	public function rules()
	{
		throw new One_Exception_Deprecated("use get".ucfirst(__FUNCTION__)."() instead");
	}
	/**
	 * @deprecated
	 */
	public function behaviors()
	{
		throw new One_Exception_Deprecated("use get".ucfirst(__FUNCTION__)."() instead");
	}

	/**
	 * @deprecated
	 */
	public function attributes()
	{
		throw new One_Exception_Deprecated('deprecated in favor of getAttributes()');
	}

	/**
	 * Return all atributes in a specific compartment.
	 * This is used for composed attributes
	 * @deprecated There is no such thing (yet) in one (these are ment to be grouped attributes)
	 */
	public function &attributesInCompartment($compartmentName = NULL)
	{
		throw new One_Exception_Deprecated('There is no such thing (yet) in one (these are ment to be grouped attributes)');
	}


	/**
	 * Search threw the scheme for the specified searchquery
	 * @deprecated
	 */
	public function search($searchval, $match = 'any')
	{
		throw new One_Exception_Deprecated('Does not exist anymore');
	}

	/**
	 * Does the scheme have (SQL)Views
	 * @deprecated
	 */
	public function hasViews()
	{
		throw new One_Exception_Deprecated('MySQL views should be refactored to seperate schemes');
	}

	/**
	 * Does the scheme have the specified (SQL)view
	 * @deprecated
	 */
	public function hasView( $view )
	{
		throw new One_Exception_Deprecated('MySQL views should be refactored to seperate schemes');
	}

	/**
	 * Get the scheme's (SQL)view
	 * @deprecated
	 */
	public function getView()
	{
		throw new One_Exception_Deprecated('MySQL views should be refactored to seperate schemes');
	}

	/**
	 * Set the scheme's (SQL)view
	 * @deprecated
	 */
	public function setView( $view )
	{
		throw new One_Exception_Deprecated('MySQL views should be refactored to seperate schemes');
	}

	/**
	 * Get all the scheme's (SQL)views
	 * @deprecated
	 */
	public function getViews()
	{
		throw new One_Exception_Deprecated('MySQL views should be refactored to seperate schemes');
	}

	/**
	 * Make a selection of the data using selectors
	 *
	 * @param array $selectors
	 * @return mixed
	 * @deprecated
	 */
	public function select( $selectors )
	{
		throw new One_Exception_Deprecated('Select should not be asked to a scheme');
		$strategy = $this->strategy;
		return $strategy->select($this, $selectors);
	}

	/**
	 * Get all columns used by the schemeAttributes
	 *
	 * @return array
	 * @deprecated
	 */
	public function columns()
	{
		throw new One_Exception_Deprecated('Columns should no longer be used');
		$columns = array();

		foreach($this->attributes as $attr) {
			$columns = array_merge($columns, $attr->columns());
		}

		foreach ($this->links as $link) {
			$columns = array_merge($columns, $link->columns());
		}

		return $columns;
	}

	/**
	 * Get the identity-attribute's column's name
	 *
	 * @return string
	 * @deprecated
	 */
	public function identityColumn()
	{
		throw new One_Exception_Deprecated('Columns should no longer be used');
		return $this->getIdentityAttribute()->getName();
	}


	/**
	 * Get the identity attribute of this scheme
	 * @return One_Scheme_Attribute
	 * @deprecated
	 */
	public function identityAttribute()
	{
		throw new One_Exception_Deprecated('Deprecated in favor of getIdentityAttribute');
	}

	/**
	 * @deprecated
	 */
	public function keyvalueAttribute()
	{
		throw new One_Exception_Deprecated("use get".ucfirst(__FUNCTION__)."() instead");
	}
}

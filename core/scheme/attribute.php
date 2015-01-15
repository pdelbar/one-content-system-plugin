<?php
//TODO: cleanup issue column/alias, no clus what alias is meant to be

/**
 * The One_Scheme_Attribute class provides an abstraction of an object attribute. It works as
 * an interface between the One_Model (and its corresponding value stored in it) and
 * objects needing to access, convert or handle the attribute.
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage Scheme
 * @filesource one/lib/scheme/attribute.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
 class One_Scheme_Attribute
{
	/**
	 * @var string The name for this attribute
	 */
	protected $_name;

	/**
	 * @var string The column for this attribute (aka. name on the store's side)
	 */
	protected $_column;

	/**
	 * @var string The alias for this attribute
	 */
	protected $_alias;

	/**
	 * @var One_Type The type object (reference) for this attribute
	 */
	protected $_type;

	/**
	 * @var array Additional parameters loaded from the scheme
	 */
	protected $_meta = array();

	/**
	 * Class constructor
	 *
	 * @param string $name
	 * @param string $type name of the One_Type of the attribute
	 * @param array additional options that can be passed to the array
	 */
	public function __construct($name, $type, array $options = array())
	{
		$this->_name = $name;
		$this->_type = One_Repository::getType($type);

		foreach($options as $key => $val)
		{
			switch($key)
			{
				case 'alias':
					$this->_alias = $val;
					break;
				case 'column':
					$this->_column = $val;
					break;
				default:
					$this->_meta[$key] = $val;
			}
		}
	}

	/**
	 * Returns the name of the attribute
	 * @todo rename to getName()
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

   public function getColumn()
   {
     return $this->_column;
   }

	/**
	 * Returns the alias of the attribute
	 * @todo rename to getAlias()
	 * @return string
	 */
	public function getAlias()
	{
		if(!is_null($this->_alias)) {
			return $this->_alias;
		}
		else {
			return $this->_name;
		}
	}

	/**
	 * Returns the type of the attribute
	 * @rename to getType()
	 * @return One_Type
	 */
	public function getType()
	{
		return $this->_type;
	}


	/**
	 * Is this attribute the identity attribute
	 *
	 * @return boolean
	 */
	public function isIdentity()
	{
		return in_array($this->_meta['identity'], array('true','yes','1'));
	}

	/**
	 * Get the default value for the attribute
	 *
	 * @return mixed
	 */
	public function getDefault()
	{
		return $this->_meta['default'];
	}

	/**
	 * Bind data to the model from an array
	 *
	 * @param One_Model $model
	 * @param array $data
	 */
	public function bindFromArray($model, $data)
	{
		$a = $this->_name;
		$model->$a = $this->_type->valueFromArray($a, $data);
	}

	/**
	 * Bind a link to the model from an array and return the value
	 *
	 * @param string $linkName
	 * @param One_Model $model
	 * @param array $data
	 * @return mixed
	 */
	public function bindLinkFromArray($linkName, One_Model &$model, array &$data)
	{
		$f = $this->_name;
		$f = $linkName . "_" . $f;
		$val = $this->_type->valueFromArray($f, $data);
		$model->$f = $val;
		return $val;
	}

	/**
	 * Bind a value to the attribute
	 *
	 * @param One_Model $model
	 */
	public function bind(One_Model $model)
	{
		$a = $this->_name;
		$related = array();

		if (isset($_REQUEST[$a]))
			$model->$a = $this->_type->valueFromArray($a, $_REQUEST);		//TODO: from context
	}

	/**
	 * Validate whether the value in the model meets the constraints set to the attribute
	 * @TODO check if this is actually still used
	 * @param One_Model $model
	 * @return array Empty array means no errors
	 * @deprecated Don't think this is used + should not be here anyway
	 */
	public function validate(One_Model $model)
	{
		throw new One_Exception_Deprecated("Don't think validate is used on the attribute itself anymore");
		$a = $this->_name;
		$value = $this->_type->valueFromArray($a, $_REQUEST);

		if ($this->_meta['required'] == 'true') {
			if (trim($value) == "") return array($a => array('required', ''));
		}
		if ($this->_meta['minimum']) {
			if (trim($value) < $this->_meta['minimum']) return array($a => array('minimum', $this->_meta['minimum']));
		}
		if ($this->_meta['maximum']) {
			if (trim($value) > $this->_meta['maximum']) return array($a => array('maximum', $this->_meta['maximum']));
		}
		return array();
	}

	/**
	 * Returns the string representation of the attribute according to the type of the attribute
	 *
	 * @param mixed $value
	 * @return string
	 */
	public function toString($value)
	{
		return $this->_type->toString($value);
	}

	/**
	 * Added magical get to be able to get meta data, avoiding the need to create a new function for every new meta data
	 *
	 * @param String $key
	 * @return Mixed
	 */
	public function __get($key)
	{
		if(array_key_exists($key, $this->_meta)) {
			return $this->_meta[$key];
		}

		return NULL;
	}

	/**
	 * Is the attribute read-only?
	 *
	 * @return boolean
	 */
	public function isReadOnly()
	{
		return isset($this->_meta['readonly']) && in_array($this->_meta['readonly'], array('true','yes','1'));
	}

	/**
	 * Is the attribute an auto-increment field
	 * A field is autoincrement by default
	 * @TODO look for better place for this check since this is pretty database related
	 *
	 * @return boolean
	 */
	public function isAutoInc()
	{
		return isset($this->_meta['autoinc']) && !in_array($this->_meta['autoinc'], array('no', '0', 'false'));
	}

	/**
	 * Should this attribute be used in a key-value pair for this scheme by default?
	 *
	 * @return boolean
	 */
	public function isKeyValue()
	{
		return isset($this->_meta['keyvalue']) && in_array($this->_meta['keyvalue'], array('true','yes','1'));
	}

	// @DEPRECATED


	/**
	 * Returns the name of the attribute
	 * @return string
	 * @deprecated
	 */
	public function name()
	{
		throw new One_Exception_Deprecated("Use get".ucfirst(__FUNCTION__)."() instead ");
	}

	/**
	 * Returns the alias of the attribute
	 * @return string
	 * @deprecated
	 */
	public function alias()
	{
		throw new One_Exception_Deprecated("Use get".ucfirst(__FUNCTION__)."() instead ");
	}

	/**
	 * Returns the type of the attribute
	 * @return One_Type
	 * @deprecated
	 */
	public function type()
	{
		throw new One_Exception_Deprecated("Use get".ucfirst(__FUNCTION__)."() instead ");
	}

	/**
	 * Get the column name for the attribute
	 * Should not be here since not every datasource has columns
	 *
	 * @return string
	 * @deprecated
	 */
	public function column() //TODO
	{
//		throw new One_Exception_Deprecated('Column should no longer be used');
		return $this->_column;
	}

	/**
	 * Get the column names for the attribute
	 * Should not be here since not every datasource has columns
	 *
	 * @return string
	 * @deprecated
	 */
	public function columns()
	{
		//throw new One_Exception_Deprecated('Column should no longer be used');
		//TODO: kill composite types
		// if the column name is set in the attribute, override type-specific settings
		$columnName = $this->meta['column'];
		if ($columnName) {
			return array($columnName);
		}
		return $this->type->columns($this->name, $this->meta);
	}


}

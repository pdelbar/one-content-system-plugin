<?php
/**
 * Parent class for all One_Types
 * One_Types are the type of an attribute (EG: int, string, text, boolean, ...)
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Type
ONEDISCLAIMER

 **/
abstract class One_Type
{
	/**
	 * @var string The name of the type
	 */
	protected $name;

	/**
	 * Get the name of the attribute
	 *
	 * @return string
	 * @abstract
	 */
	abstract public function getName();

	/**
	 * Get the columns of the fieldname
	 *
	 * @param string $fieldName
	 * @param string $fieldMeta
	 * @return array
	 */
	public function columns($fieldName, $fieldMeta)
	{
		return array($fieldName);
	}

	/**
	 * Returns the value of the requested fieldname from a given array
	 * @param $fieldName
	 * @param $data
	 * @return mixed
	 */
	public function valueFromArray($fieldName, $data)
	{
		return $data[$fieldName];
	}

	/**
	 * Returns the value of the type converted to a string.
	 * This will be specific for any type
	 *
	 * @param mixed $value
	 * @return string
	 */
	public function toString($value)
	{
		return $value;
	}

	/**
	 * Returns the name of the type
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
	}

	// Deprecated functions
	public function name()
	{
		throw new One_Exception_Deprecated('Use getName instead');
	}

  public function defaultWidgetClass()
  {
    return 'One_Form_Widget_Scalar_Textfield';
  }
}

<?php
/**
 * Create a calculated field, this field should not be included in Query_Objects (for now)
 *
 * @author traes
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage Type
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Type_Calculated extends One_Type
{
	/**
	 * @var string Name of the calculated field
	 */
	protected static $_calcAttrName = null;

	public function __construct() {}

	/**
	 * Get the name of the attribute
	 *
	 * @return string
	 */
	public function getName()
	{
		return "Calculated";
	}

	protected static function getAttributeName()
	{
		return self::$_calcAttrName;
	}

	protected static function setAttributeName($attrName)
	{
		self::$_calcAttrName = $attrName;
	}

	/**
	 * Returns the value as a date value
	 *
	 * @return string
	 */
	public function toString($value)
	{
		return $value;
	}

	/**
	 * Calculate the value for the field
	 * @param One_Model $model
	 * @return Mixed
	 */
	public function calculate(One_Model $model)
	{
		$calculated = $model->dpFirstName.' '.$model->dpLastName;
		return $calculated;
	}

	/**
	 * Order a list of models by the calculated field
	 * @param array $list
	 * @return list
	 */
	public static function orderList(array $list = array(), $direction = 'asc')
	{
		if('desc' == $direction) {
			usort($list, array(self, 'usortDesc'));
		}
		else {
			usort($list, array(self, 'usortAsc'));
		}
		return $list;
	}

	protected static function usortAsc($a, $b)
	{
		$attrName = self::getAttributeName();
		return strcasecmp($a->$attrName, $b->$attrName);
	}

	protected static function usortDesc($a, $b)
	{
		$attrName = self::getAttributeName();
		return strcasecmp($b->$attrName, $a->$attrName);
	}
}

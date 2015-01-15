<?php
/**
 * Handles search option for int-ranges
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/form/widget/search/op/intrange.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Form_Widget_Search_Op_Intrange extends One_Form_Widget_Search_Op_Abstract
{
	/**
	 * Class constructor
	 *
	 * @param One_Form_Widget_Abstract $forWidget
	 */
	public function __construct( $forWidget )
	{
		throw new One_Exception_NotImplemented('Intrange has not yet been implemented');
		$this->forWidget = $forWidget;
		$this->type      = 'intrange';
	}

	/**
	 * Render the op
	 */
	public function render()
	{
		$data = array(
						'for'   => $this->forWidget,
						'value' => $this->getValue()
					);

		$content = $this->parse( $data );
		return $content;
	}

	/**
	 * Affect the One_Query appropriately
	 *
	 * @param One_Query $q
	 * @param string $attrName
	 * @param mixed $attrVal
	 * @final
	 */
	final public function affectQuery( One_Query $q, $attrName, $attrVal )
	{
		//@TODO finish this op
	}

	/**
	 * Get the allowed operators
	 *
	 * @return array
	 * @final
	 */
	final public function getAllowed()
	{
		return array(
						'default' => 'eq',
						'eq',
						'neq',
						'lt',
						'lte',
						'gt',
						'gte'
					);
	}
}
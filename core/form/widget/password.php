<?php
/**
 * Handles the password widget
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/form/widget/password.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
Class One_Form_Widget_Password extends One_Form_Widget_Scalar_Textfield
{
	/**
	 * Class constructor
	 *
	 * @param string $id
	 * @param string $name
	 * @param string $label
	 * @param array $config
	 */
	public function __construct( $id = NULL, $name = '', $value = NULL, $label = NULL, array $config = array() )
	{
		parent::__construct($id, $name, $value, $label, $config );
		$this->_type = 'passwordfield';
		$this->_totf = 'password';
	}

	/**
	 * Return the allowed options for this widget
	 *
	 * @return array
	 */
	protected static function allowedOptions()
	{
		$additional = array(
							'dir' => 1,
							'lang' => 1,
							'xml:lang' => 1,
							'disabled' => 1,
							'size' => 1,
							'fillInPw' => 2
							);
		return array_merge(parent::allowedOptions(), $additional);
	}

	/**
	 * Overrides PHP's native __toString function
	 *
	 * @return string
	 */
	public function __toString()
	{
		return get_class() . ': ' . $this->getID();
	}
}

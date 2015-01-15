<?php
/**
 * The widgetFactory creates instances of widgets
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/form/widget/factory.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
Class One_Form_Widget_Factory
{
	/**
	 * Creates an instance of the specified widget
	 *
	 * @param string $type
	 * @return One_Form_Widget_Abstract
	 */
	public static function getInstance( $type )
	{
		$class = 'One_Form_Widget_' . ucfirst( strtolower( $type ) );

		if(class_exists($class))
		{
			$new = new $class();
			return $new;
		}
		else
			throw new One_Exception('There is no widget with type "' . $type . '"');
	}
}

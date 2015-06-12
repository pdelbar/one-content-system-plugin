<?php
/**
 * Treats a scheme-attribute as a text
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Type
ONEDISCLAIMER

 **/
class One_Type_Text extends One_Type
{
	/**
	 * Get the name of the attribute
	 *
	 * @return string
	 */
	public function getName()
	{
		return "text";
	}

	/**
	 * Returns the value as a string value
	 *
	 * @return string
	 */
	public function toString($value)
	{
		return '"'.$value.'"';
	}

  public function defaultWidgetClass()
  {
    return 'One_Form_Widget_Scalar_Textarea';
  }
}

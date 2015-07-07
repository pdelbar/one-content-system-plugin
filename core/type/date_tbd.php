<?php
/**
 * Treats a scheme-attribute as a date
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Type
ONEDISCLAIMER

 **/
class One_Type_Date extends One_Type
{
	/**
	 * Get the name of the attribute
	 *
	 * @return string
	 */
	public function getName()
	{
		return "date";
	}

	/**
	 * Returns the value as a date value
	 *
	 * @return string
	 */
	public function toString($value)
	{
		return '"'.$value.'"';
	}


  public function defaultWidgetClass()
  {
    return 'One_Form_Widget_Scalar_Date';
  }
}

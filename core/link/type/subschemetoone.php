<?php
/**
 *  The oneToMany link type is used to link this object to many other
 *  objects. To do that, these objects must keep a foreign key value to
 *  this object.
 *
 * 	The foreign key included consists of all identity fields of the target.
 *


  * @TODO review this file and clean up historical code/comments
ONEDISCLAIMER

 **/
class One_Link_Type_Subschemetoone extends One_Link_Type_Onetomany
{
	/**
	 * Returns the name of this linktype
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'subschemetoone';
	}

	/**
	 * Return all related items
	 *
	 * @return array
	 */
	public function getRelated(One_Link_Interface $link, One_Model $model, array $options = array())
	{
		return null;
	}

	/**
	 * Overload toString function
	 * @param mixed
	 * @return string
	 */
	public function toString( $value )
	{
		return $value;
	}
}

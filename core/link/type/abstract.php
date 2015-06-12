<?php
/**
 * One_Link_Type is the way how the relationship is built.
 * One-to-many, many-to-one or many-to-many
 *


  * @TODO review this file and clean up historical code/comments
ONEDISCLAIMER

 * @abstract
 **/
abstract class One_Link_Type_Abstract implements One_Link_Type_Interface
{
	/**
	 * Returns the name of the linktype
	 *
	 * @return string
	 * @deprecated
	 */
	public function name()
	{
		throw new One_Exception_Deprecated('Use getName() instead');
	}

	/**
	 *  Return the columns to insert into the load request to be able to
	 *  recreate the link
	 *
	 * @param One_Link $link
	 * @return array
	 */
	public function columns(One_Link_Interface $link)
	{
		return array();
	}

	/**
	 * Returns the string value of the value
	 *
	 * @param mixed $value
	 * @return string
	 */
	public function toString( $value )
	{
		return $value;
	}

	/**
	 * Overrides default toString method
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
	}

	/**
	 *  find out what the scheme's FK field for this relationship is called.
	 *  By default, this is formed by
	 *  		ROLENAME_NAMEOFTARGETIDCOLUMN,
	 *  but this can be overridden by the FK setting in the meta description.
	 *
	 * @param One_Link $link
	 * @param One_Scheme $target
	 * @return string
	 */
	public function localFK(One_Link_Interface $link, One_Scheme $target )
	{
		if($link->meta['fk:local']) {
			return $link->meta['fk:local'];
		}

		$col = $target->getIdentityAttribute()->getName();
		return $link->getName() . '_' . $col;
	}

	/**
	 *  find out what the scheme's FK field for this relationship is called.
	 *  By default, this is formed by
	 *  		ROLENAME_NAMEOFTARGETIDCOLUMN,
	 *  but this can be overridden by the FK setting in the meta description.
	 *
	 * @param One_Link $link
	 * @param One_Scheme $source
	 * @param One_Link $backlink
	 * @return string
	 */
	public function remoteFK(One_Link_Interface $link, One_Scheme $source, One_Link_Interface $backlink )
	{
		if($link->meta['fk:remote']) {
			return $link->meta['fk:remote'];
		}

		$column = $source->getIdentityAttribute()->getName();
		return $backlink->getName() . "_" . $column;
	}
}

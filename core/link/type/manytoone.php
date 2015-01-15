<?php
/**
 * The manyToOne link type works as follows :
 *
 *	A1( id, afield, FK_b )	---	+
 *									    +
 *	A2( id, afield, FK_b )	---	+---(n:1)---	B( id, bfield )
 *									   	+
 *	A3( id, afield, FK_b )	---	+
 *
 *	The cardinality as seen from A decides what happens:
 *		n:1	A includes a foreign key to B (see above)
 *		1:n	A does not include a foreign key (see above from the
 *				perspective of B)
 *		1:1	A and B both include the foreign key
 *		n:n	cannot be done using the FK principle
 *
 *	The foreign key included consists of all identity fields of the target.
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/link/type/manytoone.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Link_Type_Manytoone extends One_Link_Type_Abstract
{
	/**
	 * Returns the name of this linktype
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'manytoone';
	}

	/**
	 * columns to be included depends on which side of the relationship
	 * we are on : if the cardinality is n:1 from this side, we need to include
	 * the identity fields of the target type
	 * @see One_Link_Type::columns()
	 * @deprecated Tom thinks this is no longer being used
	 */
	public function columns(One_Link $link)
	{
		throw new One_Exception_Deprecated('Tom thinks this is no longer being used');
		// determine the fields to bind and bind them
		$target = One_Repository::getScheme( $link->getTarget() );
		$cols = $target->identityColumns( $link );
		$linkCols = array();
		foreach ($cols as $col) $linkCols[] = $link->getName() . "_" . $col;
		return $linkCols;
	}


	/**
	 * Return all related items
	 *
	 * @return array
	 */
	public function getRelated(One_Link_Interface $link, One_Model_Interface $model, array $options = array())
	{
		$linkName = $link->getName();

		// identify the target scheme
		$source = One_Repository::getScheme( $model->getSchemeName() );
		// PD 22OCT08
		if ($link->getTarget() == '*') {
			$col = $link->getName() . '_scheme';
			$target = One_Repository::getScheme( $model->$col );
		}
		else
			$target = One_Repository::getScheme( $link->getTarget() );

		// determine the target's identity column
		$column = $this->localFK( $link, $target );

		// bind the data using the data
		$localValue = $model[$column];

		// create query and execute
		return One_Repository::selectOne( $target->getName(), $localValue );
	}

	public function countRelated(One_Link_Interface $link, One_Model_Interface $model, array $options = array())
	{
		$linkName = $link->getName();

		// identify the target scheme
		$source = One_Repository::getScheme( $model->getSchemeName() );
		// PD 22OCT08
		if ($link->getTarget() == '*') {
			$col = $link->getName() . '_scheme';
			$target = One_Repository::getScheme( $model->$col );
		}
		else
			$target = One_Repository::getScheme( $link->getTarget() );

		// determine the target's identity column
		$column = $this->localFK( $link, $target );

		// bind the data using the data
		$localValue = $model[$column];

		// create query and execute
		return (One_Repository::selectOne( $target->getName(), $localValue )) ? 1 : 0;
	}

	/**
	 * Return the string version of the value
	 *
	 * @return string
	 */
	public function toString( $value )
	{
		return $value;
	}
}

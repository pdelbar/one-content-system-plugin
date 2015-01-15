<?php
/**
 *  The oneToMany link type is used to link this object to many other
 *  objects. To do that, these objects must keep a foreign key value to
 *  this object.
 *
 * 	The foreign key included consists of all identity fields of the target.
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/link/type/onetomany.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Link_Type_Onetomany extends One_Link_Type_Abstract
{
	/**
	 * Returns the name of this linktype
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'onetomany';
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
		$target = One_Repository::getScheme( $link->getTarget() );

		$backlinks = $target->getLinks();
		$backlink = $backlinks[ $link->getLinkId() ];
		if (!$backlink) {
			throw new One_Exception( 'The role "' . $roleName . '" does not exist for this model' );
		}

		$at = $source->getIdentityAttribute()->getName();
		$column = $this->remoteFK( $link, $source, $backlink );

		// bind the data using the data
		$localValue = $model->$at;

		// create query and execute
		$q = One_Repository::selectQuery( $link->getTarget() );
		$q->setOptions( $options );
		if (isset($link->meta['hybrid'])) {
			$var = $link->meta['hybrid'] . '_scheme';
			$q->where( $var, 'eq', $source->getName() );
		}
		$q->where( $column, 'eq', $localValue );
		return $q->execute();
	}

	public function countRelated(One_Link_Interface $link, One_Model_Interface $model, array $options = array())
	{
		$linkName = $link->getName();

		// identify the target scheme
		$source = One_Repository::getScheme( $model->getSchemeName() );
		$target = One_Repository::getScheme( $link->getTarget() );

		$backlinks = $target->getLinks();
		$backlink = $backlinks[ $link->getLinkId() ];
		if (!$backlink) {
			throw new One_Exception( 'The role "' . $roleName . '" does not exist for this model' );
		}

		$at = $source->getIdentityAttribute()->getName();
		$column = $this->remoteFK( $link, $source, $backlink );

		// bind the data using the data
		$localValue = $model->$at;

		// create query and execute
		$q = One_Repository::selectQuery( $link->getTarget() );
		$q->setOptions( $options );
		if (isset($link->meta['hybrid'])) {
			$var = $link->meta['hybrid'] . '_scheme';
			$q->where( $var, 'eq', $source->getName() );
		}
		$q->where( $column, 'eq', $localValue );
		return $q->getCount();
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

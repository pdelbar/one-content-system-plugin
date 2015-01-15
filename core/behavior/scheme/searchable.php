<?php
/**
 * Makes a scheme searchable
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/behavior/scheme/searchable.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Behavior_Scheme_Searchable extends One_Behavior_Scheme
{
	/**
	 * Return the name of the behaviour
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'searchable';
	}

	/**
	 * Adds the fields that are searchable to the scheme on loading of the scheme
	 *
	 * @param One_Scheme $scheme
	 */
	public function onLoadScheme( $scheme )
	{
		$options = $scheme->getBehaviorOptions( 'searchable' );

		if( is_null( $options['search'] ) || trim( $options['search'] ) == '' )
			throw new One_Exception( 'When defining a searchable behavior, you must define an attribute.' );

		$scheme->oneSearchableFields = explode( ':', $options['search'] );
	}
}

<?php
/**
 * Authorisation-rule to allow a task in the Joomla! administrator application
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/permission/rule/jbackend.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Permission_Rule_Jbackend extends One_Permission_Rule // @TODO move to custom
{
	public function __construct( $options = array() )
	{
		parent::__construct( $options );
		$this->rules = array();
	}

	public function authorize( $args )
	{
		$app = JFactory::getApplication();
		return ( preg_match( '/admin(istrator)?/i', $app->getName() ) > 0 );
	}
}

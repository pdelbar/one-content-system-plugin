<?php
/**
 * Authorisation-rule to always allow a task
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/permission/rule/always.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Permission_Rule_Always extends One_Permission_Rule
{
	public function __construct( $options = array() )
	{
		parent::__construct( $options );
		$this->rules = array();
	}

	public function authorize( array $args )
	{
		return true;
	}
}

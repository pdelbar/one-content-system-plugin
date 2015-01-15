<?php
/**
 * Class that handles the authorisation of a single rule
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/permission/rule.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Permission_Rule implements One_Permission_Rule_Interface
{
	/**
	 * @var array Options passed along to the rule
	 */
	protected $options;

	/**
	 * Class constructor
	 *
	 * @param array $options
	 */
	public function __construct( $options = array() )
	{
		$this->options = $options;
	}

	/**
	 * Authorise the current rule
	 *
	 * @param array $args
	 * @return boolean
	 */
	public function authorize( $args )
	{
		if( trim( $this->options['type'] ) != '' )
		{
			$className = 'One_Permission_Rule_' . ucFirst( $this->options['type'] );
			$ruler = new $className(  $this->options );
			$result = $ruler->authorize( $args );

			// negate if the "not" option is set to true
			if( $this->options['not'] )
				return !$result;
			else
				return $result;
		}
		else
			throw new One_Exception( 'Invalid rule' );
	}
}

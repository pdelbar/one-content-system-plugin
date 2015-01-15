<?php
/**
 * Adds a published behavior to a scheme
 * It allows to see whether or not a scheme can be (un)publishable
 * and also specify which attribute is used to determine if the model is published or not ( "published" by default )
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/behavior/scheme/published.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Behavior_Scheme_Published extends One_Behavior_Scheme
{
	/**
	 * Return the name of the behaviour
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'published';
	}

}

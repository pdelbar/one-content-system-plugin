<?php
/**
 * Class that handles the authorisation of a user to perform a certain task
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/permission/permission.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Permission
{
	/**
	 * Is the user authorised to perform the current task
	 *
	 * @param string $task
	 * @param string $schemeName
	 * @param mixed $id
	 * @return boolean
	 */
	public static function authorize($task, $schemeName, $id)
	{
		// @TODO try defined rule, currently overrides everything
		$scheme = One_Repository::getScheme($schemeName);
		$rules = $scheme->getRules($task);

		// @TODO if none, create generic (behavior-inspired) ruleset
		if(count($rules))
		{
			foreach($rules as $rule)
			{
				if(!$rule->authorize(array('scheme' => $schemeName, 'id' => $id))) {
					self::refuse($task, $schemeName, $id);
				}
			}
		}
		else if(!in_array($task, array('list', 'detail', 'show', 'jqgrid'))) {
			self::refuse($task, $schemeName, $id);
		}

		return false;
	}

	/**
	 * Refuse the user to perform the current task
	 *
	 * @param string $task
	 * @param string $schemeName
	 * @param mixed $id
	 */
	public static function refuse($task, $schemeName, $id)
	{
		$idTxt = '';
		if(!is_null($id) && $id != 0 && $id != '') {
			 $idTxt = '&id=' . $id;
		}

		throw new One_Exception('You are not allowed to perform the task "'.$task.'" on the scheme "'.$schemeName.'"');
	}
}

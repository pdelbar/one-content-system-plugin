<?php
/**
 * This class handles the list view of the chosen item
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/action/list.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Action_Json extends One_Action_List
{
	/**
	 * This method returns the list view of the currently chosen item
	 *
	 * @return string The list view of the currently chosen item
	 */
	public function execute()
	{
		$this->authorize();

		$results = $this->getData();

		$jArray = array();
		foreach($results as $result) {
			$jArray[] = $result->toArray();
		}

		header('Content-type: application/json');
		header("Cache-Control: no-cache");
		header("Pragma: no-cache");
		header("Expires: Fri, 01 Jan 2010 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		echo json_encode($jArray);
		exit;
	}

	/**
	 * Returns whether the user is allowed to perform this task
	 *
	 * @return boolean
	 */
	public function authorize()
	{
		return true;
		return One_Permission::authorize('json', $this->scheme, 0);
	}
}

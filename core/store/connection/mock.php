<?php
/**
 * The One_Store_Connection_Mock class supplies a mock connection
 *
 * @author traes
 * @copyright 2011 delius bvba
  * @TODO review this file and clean up historical code/comments
 * @subpackage Store
 **/
class One_Store_Connection_Mock extends One_Store_Connection_Abstract
{
	/**
	 * Open the connection
	 */
	public function open()
	{
		return null;
	}

	/**
	 * Close the connection
	 */
	public function close($ch = NULL)
	{
		return null;
	}
}

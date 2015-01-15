<?php
/**
 * Interface used for One_Filters
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/filter/interface.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
Interface One_Filter_Interface
{
	/**
	 * Function that will affect the One_Query
	 *
	 * @param One_Query_Interface $query
	 */
	public function affect(One_Query $query);
}

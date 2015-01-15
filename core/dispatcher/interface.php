<?php
/**
 * The One_Dispatcher class gets the correct controller and executes it.
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/dispatcher/dispatcher.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
interface One_Dispatcher_Interface
{

	public function __construct(array $options = array());

	/**
	 * @return One_Dispatcher_Interface
	 */
	public function dispatch();

	/**
	 * Set the redirect options
	 * @param array
	 * @return One_Dispatcher
	 */
	public function setRedirect(array $options);

	/**
	 * Get the redirect options
	 *
	 * @return One_Dispatcher
	 */
	public function redirect();
}
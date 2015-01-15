<?php
/**
 * This class handles a view for the current scheme that doesn't need a model
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/action/Show.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Action_Show extends One_Action
{
	/**
	 * Class constructor
	 *
	 * @param One_Controller $controller
	 * @param array $options
	 */
	public function __construct(One_Controller $controller, $options = array())
	{
		parent::__construct($controller, $options);

		$view = $this->getVariable('view', 'show');
		$this->view = new One_View($this->scheme, $view);
	}

	/**
	 * This method returns the detail view of the currently chosen item
	 *
	 * @return string The detail view of the currently chosen item
	 */
	public function execute()
	{
		$this->authorize($this->scheme->getName());
		$this->view->setAll($this->options);
		$this->view->set('scheme', $this->scheme);
		return $this->view->show();
	}

	/**
	 * Returns whether the user is allowed to perform this task
	 *
	 * @param One_Scheme $scheme
	 * @param mixed $id
	 * @return boolean
	 */
	public function authorize($scheme)
	{
		return One_Permission::authorize('show', $scheme, NULL);
	}
}

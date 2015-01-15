<?php
/**
 * This is the parent class of all actions.
 * This class should never be instanciated on it's own
 * @todo if that's true, it should be abstract
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/action/action.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Action implements One_Action_Interface
{
	/**
	 * @var One_Controller The controller used for the current action
	 */
	protected $controller;

	/**
	 * @var array Any additional options passed to the current action
	 */
	protected $options = array();

	/**
	 * @var One_View The One_View instance used for the current action
	 */
	protected $view;

	/**
	 * @var One_Scheme_Interface The One_Scheme_Interface used for the current action
	 */
	protected $scheme;

	/**
	 * Class constructor
	 *
	 * @param One_Controller $controller
	 * @param array $options
	 */
	public function __construct(One_Controller $controller, array $options = array())
	{
		$this->controller = $controller;
		$this->options    = $options;

		if(!($this->scheme instanceof One_Scheme_Interface)) {
			$this->scheme = One_Repository::getScheme($this->options['scheme']);
		}
	}

	/**
	 * Gets the variable passed to the method out of the context.
	 * If the variable is not set, return the default value.
	 *
	 * @param string $key The required variable
	 * @param mixed $default The default value that should be returned in case the required variable is not set
	 * @return mixed
	 */
	protected function getVariable($key, $default = NULL)
	{
		if(array_key_exists($key, $this->options) && !is_null($this->options[$key])) {
			return $this->options[$key];
		}
		else {
			return $default;
		}
	}

	/**
	 * Set the One_View for the current action
	 *
	 * @param One_View $view
	 */
	public function setView($view)
	{
		$this->view = $view;
	}

	/**
	 * Returns the controller for the current action
	 *
	 * @return One_Controller
	 */
	public function getController()
	{
		return $this->controller;
	}

	/**
	 * Execute the actions needed for the current action
	 * This method should be overridden in the subclasses.
	 */
	public function execute(){}

	/**
	 * Processes any possibly given filters and alters the One_Query object accordingly
	 *
	 * @param One_Query_Interface $query
	 * @param array $filters
	 */
	protected function processQueryConditions(One_Query_Interface $query, array $filters)
	{
		$condition = $this->getVariable('query', '');
		if ($condition) {
			$filters = array();
			parse_str($this->getVariable('filters', ''), $filters);

			$c = One_Repository::getFilter($condition, $query->getScheme()->getName(), $filters);
			$c->affect($query);
		}
	}

    /**
     * Replace redirect variables if needed
     * @param array $redirect
     * @return array
     */
    protected function replaceOtherVariables(array $redirect = array())
    {
        foreach($redirect as $key => $value)
        {
            if(preg_match('/^\:\:([^\:]+)\:\:$/', trim($value), $matches) > 0)
            {
                if(array_key_exists($matches[1], $this->options)) {
                    $redirect[$key] = $this->options[$matches[1]];
                }
                elseif(array_key_exists('oneForm', $this->options) && array_key_exists($matches[1], $this->options['oneForm'])) {
                	$redirect[$key] = $this->options['oneForm'][$matches[1]];
                }
                else {
                	$redirect[$key] = '';
                }
            }
        }

        return $redirect;
    }

  /**
   * Return the standard routing for this action. Should be null, it needs to be set by the subclasses
   */
  public static function getStandardRouting($options) {
    return null;
  }
}

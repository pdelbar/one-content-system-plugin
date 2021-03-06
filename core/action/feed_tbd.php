<?php
/**
 * This action handles how to return a feed of the chosen scheme
 *


  * @TODO review this file and clean up historical code/comments
ONEDISCLAIMER

 **/
class One_Action_Feed extends One_Action
{
	/**
	 * Class constructor
	 *
	 * @param One_Controller $controller
	 * @param array $options
	 */
	public function __construct( One_Controller $controller, $options = array() )
	{
		parent::__construct( $controller, $options );

		$view = $this->getVariable( 'view', 'rss' );
		$this->view = new One_View( $this->scheme, $view);
	}

	/**
	 * This method will return a feed for the chosen scheme
	 *
	 * @return string The feed string
	 */
	public function execute()
	{
		$this->authorize();

		$factory = One_Repository::getFactory( $this->scheme->getName() );
		$q = $factory->selectQuery();

		$q->setOrder( $this->getVariable( 'order', '' ) );
		$q->setLimit( $this->getVariable( 'count', 0 ), $this->getVariable( 'start', 0 ) );

		$this->processQueryConditions( $q, $filters );

		$results = $q->result( false );

		$this->view->set( 'scheme', $this->scheme );
		$this->view->setModel( $results );
		echo $this->view->show();
		exit; // exit here because nothing else should be outputted after the feed
	}

	/**
	 * Processes any possibly given filters and alters the One_Query object accordingly
	 *
	 * @param One_Query $q
	 * @param array $filters
	 */
	private function processQueryConditions( $q, $filters )
	{
		$condition = $this->getVariable( 'query', '' );
		if ($condition) {
			$filters = array();
			parse_str( $this->getVariable('filters', ''), $filters );

			$c = One_Repository::getFilter( $condition, $q->getScheme()->getName(), $filters );
			$c->affect( $q );
		}
	}

	/**
	 * Returns whether the user is allowed to perform this task
	 *
	 * @param One_Scheme $scheme
	 * @param mixed $id
	 * @return boolean
	 */
	public function authorize()
	{
		return true;
	}
}

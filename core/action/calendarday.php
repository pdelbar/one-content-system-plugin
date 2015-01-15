<?php
/**
 * This class takes care of the calendar task for a single day
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/action/calendar/day.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Action_CalendarDay extends One_Action_Calendar
{
	/**
	 * Class constructor
	 *
	 * @param One_Controller $controller The controller being used for this action
	 * @param array $options Additional options
	 */
	public function __construct( One_Controller $controller, $options = array() )
	{
		parent::__construct( $controller, $options );
		$this->view = new One_View( $this->parentAction->scheme, 'day' );
	}

	/**
	 * Executes the necesary queries and gets the view for the day-calendar
	 * Returns the output of the day-calendar view
	 * @return string
	 */
	public function execute()
	{
		$this->authorize();

		$dateVar = $this->parentAction->dateAttribute;
		$timeVar = $this->parentAction->timeAttribute;

		$factory = One_Repository::getFactory( $this->parentAction->scheme );
		$q       = $factory->selectQuery();

		$d = mktime(0,0,0,$this->parentAction->month,$this->parentAction->day,$this->parentAction->year);

		// limit to the selected month
		$q->where( $dateVar, 'eq', date( 'Y-m-d', $d ) );

		$this->processQueryConditions( $q, $filters );

		if( !is_null( $timeVar ) )
			$q->setOrder( $timeVar );
		else
			$q->setOrder( $this->getVariable( 'order', '' ) );

		$q->setLimit( $this->getVariable( 'count', 0 ), $this->getVariable( 'start', 0 ) );

		$result = $q->result();

		$this->view->setModel( $result );
		$this->view->set( 'scheme', $this->parentAction->scheme );
		$this->view->set( 'oneCday', $this->parentAction->day );
		$this->view->set( 'oneCmonth', $this->parentAction->month );
		$this->view->set( 'oneCyear', $this->parentAction->year );
		$this->view->set( 'yesterday', strtotime( "- 1 days", $d ) );
		$this->view->set( 'tomorrow', strtotime( "+ 1 days", $d ) );
		$this->view->set( 'dateAttribute', $dateVar );
		$this->view->set( 'timeAttribute', $this->parentAction->timeAttribute );
		$this->view->set( 'titleAttribute', $this->parentAction->titleAttribute );

		return $this->view->show();
	}

	/**
	 * Processes any possibly given filters and alters the One_Query object accordingly
	 *
	 * @param One_Query $q
	 * @param array $filters
	 */
	protected function processQueryConditions( $q, $filters )
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
	public function authorize( $scheme, $id )
	{
		return One_Permission::authorize( 'calendar', $scheme, $id );
	}
}

<?php
/**
 * This class takes care of the calendar task for a single week
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/action/calendar/week.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Action_Calendar_Week extends One_Action_Sub
{
	/**
	 * @var boolean Should the weekcalendar start on a monday or sunday?
	 */
	public $startOnMonday;

	/**
	 * Class constructor
	 *
	 * @param One_Controller $controller The controller being used for this action
	 * @param array $options Additional options
	 */
	public function __construct( One_Controller $controller, $options = array() )
	{
		$calendar = new One_Action_Calendar( $controller, $options );
		parent::__construct( $calendar );

		$scheme              = One_Repository::getScheme( $this->parentAction->scheme );
		$calendarOptions     = $scheme->getBehaviorOptions( 'calendar');
		$this->startOnMonday = ( ( $calendarOptions[ 'startOnMonday' ] == "true" ) ? true : false );

		$this->view = new One_View( $this->parentAction->scheme, 'week' );
	}

	/**
	 * Executes the necesary queries and gets the view for the week-calendar
	 *
	 * @return string Returns the output of the week-calendar view
	 */
	public function execute()
	{
		$this->authorize( $this->parentAction->scheme );

		$dateVar = $this->parentAction->dateAttribute;
		$timeVar = $this->parentAction->timeAttribute;

		$factory =  One_Repository::getFactory( $this->parentAction->scheme );
		$q       = $factory->selectQuery();

		// limit to the selected week
		$d = mktime( 0, 0, 0, $this->parentAction->month, $this->parentAction->day, $this->parentAction->year );

		if( $this->startOnMonday )
		{
			$dayOfWeek = date( 'N', $d );
			if( $dayOfWeek > 1 )
				$d = strtotime( "-" . ( $dayOfWeek - 1 ) . " days", $d );
		}
		else
		{
			$dayOfWeek = date( 'N', $d ) % 7;
			if( $dayOfWeek > 1 )
				$d = strtotime( "-" . $dayOfWeek . " days", $d );
		}

		$q->where( $dateVar, 'gte', date( 'Y-m-d', $d ) );
		$q->where( $dateVar, 'lt', date( 'Y-m-d', strtotime("+ 7 days", $d) ) );

		$this->processQueryConditions( $q, $filters );

		if( trim( $this->getVariable( 'order', '' ) ) != '' )
			$q->setOrder( $this->getVariable( 'order', $dateVar ) );
		else if( !is_null( $dateVar ) && !is_null( $timeVar ) )
			$q->setOrder( array( $dateVar, $timeVar ) );
		else
			$q->setOrder( $dateVar );

		$q->setLimit( $this->getVariable( 'count', 0 ), $this->getVariable( 'start', 0 ) );

		$result = $q->result();

		$this->view->setModel( $result );
		$this->view->set( 'scheme', $this->parentAction->scheme );
		$this->view->set( 'oneCday', $this->parentAction->day );
		$this->view->set( 'oneCmonth', $this->parentAction->month );
		$this->view->set( 'oneCyear', $this->parentAction->year );
		$this->view->set( 'prevWeek', strtotime( "- 7 days", $d ) );
		$this->view->set( 'nextWeek', strtotime( "+ 7 days", $d ) );
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
	public function authorize( $scheme )
	{
		return One_Permission::authorize( 'calendar', $scheme, NULL );
	}
}

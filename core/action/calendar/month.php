<?php
/**
 * This class takes care of the calendar task for a single month
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/action/calendar/month.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Action_Calendar_Month extends One_Action_Sub
{
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
		$this->view = new One_View( $this->parentAction->scheme, 'month' );
	}

	/**
	 * Executes the necesary queries and gets the view for the month-calendar
	 *
	 * @return string Returns the output of the month-calendar view
	 */
	public function execute()
	{
		$this->authorize();

		$dateVar = $this->parentAction->dateAttribute;
		$timeVar = $this->parentAction->timeAttribute;

		$factory = One_Repository::getFactory( $this->parentAction->scheme );
		$q       = $factory->selectQuery();

		// limit to the selected month
		$q->where( $dateVar, 'gte', date( 'Y-m-d', mktime( 0, 0, 0, $this->parentAction->month, 1, $this->parentAction->year ) ) );
		$q->where( $dateVar, 'lt', date( 'Y-m-d', mktime( 0, 0, 0, 1 + ( $this->parentAction->month % 12 ) , 1, $this->parentAction->year + ( $this->parentAction->month == 12 ? 1 : 0 ) ) ) );

		$this->processQueryConditions( $q, $filters );

		if( trim( $this->getVariable( 'order', '' ) ) != '' )
			$q->setOrder( $this->getVariable( 'order', $dateVar ) );
		else if( !is_null( $dateVar ) && !is_null( $timeVar ) )
			$q->setOrder( array( $dateVar, $timeVar ) );
		else
			$q->setOrder( $dateVar );

		$q->setLimit( $this->getVariable( 'count', 0 ), $this->getVariable( 'start', 0 ) );

		// prepare data
		$ndays = cal_days_in_month( CAL_GREGORIAN, $this->parentAction->month, $this->parentAction->year );
		$model = array();
		for( $i=1; $i <= $ndays ; $i++ )
		{
			$model[$i] = array();
		}
		$result = $q->result();
		foreach( $result as $item )
		{
			$d = date_parse( $item->$dateVar );
			$model[ $d['day'] ][] = $item;
		}

		$this->view->setModel( $model );
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
	public function authorize( $scheme, $id )
	{
		return One_Permission::authorize( 'calendar', $scheme, $id );
	}
}

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
class One_Action_CalendarMonth extends One_Action_Calendar
{
	/**
	 * Class constructor
	 *
	 * @param One_Controller $controller The controller being used for this action
	 * @param array $options Additional options
	 */
	public function __construct(One_Controller $controller, $options = array())
	{
		parent::__construct($controller, $options);
		$this->view = new One_View($this->scheme, 'month');
	}

	/**
	 * Executes the necesary queries and gets the view for the month-calendar
	 *
	 * @return string Returns the output of the month-calendar view
	 */
	public function execute()
	{
		$this->authorize($this->scheme);

		$dateVar    = $this->dateAttribute;
		$enddateVar = $this->enddateAttribute;
		$timeVar    = $this->timeAttribute;

		// Use Zend_Locale to easily get the names of the days and month for the current language
		$locale = new Zend_Locale(str_replace('-', '_', One::getInstance()->getLanguage()));

		$monthNames = $locale->getTranslationList('months', str_replace('-', '_', One::getInstance()->getLanguage()));
		$currMonthName = $monthNames['format']['wide'][intval($this->month)];

		$dayNames = $locale->getTranslationList('days', str_replace('-', '_', One::getInstance()->getLanguage()));
		$dayNames = array_keys(array_flip($dayNames['format']['abbreviated']));

		$firstCalendarDay = date('w', mktime(0, 0, 0, $this->month, 1, $this->year));
		$calendarDays = array();
		$currDay = $firstCalendarDay;
		for($i = 0; $i < 7; $i++) {
			$calendarDays[$i] = $dayNames[$currDay];
			$currDay++;
			if($currDay > 6) {
				$currDay = 0;
			}
		}

		$factory = One_Repository::getFactory($this->scheme);
		$q       = $factory->selectQuery();

		// limit to the selected month
		if(null === $this->enddateAttribute)
		{
			$q->where($dateVar, 'gte', date('Y-m-d', mktime(0, 0, 0, $this->month, 1, $this->year)));
			$q->where($dateVar, 'lt', date('Y-m-d', mktime(0, 0, 0, 1 + ($this->month % 12) , 1, $this->year + ($this->month == 12 ? 1 : 0))));
		}
		else
		{
			$or   = $q->addOr();

			$and1 = $or->addAnd($q);
			$and1->where($dateVar, 'gte', date('Y-m-d', mktime(0, 0, 0, $this->month, 1, $this->year)));
			$and1->where($dateVar, 'lt', date('Y-m-d', mktime(0, 0, 0, 1 + ($this->month % 12) , 1, $this->year + ($this->month == 12 ? 1 : 0))));

			$and2 = $or->addAnd($q);
			$and2->where($dateVar, 'lt', date('Y-m-d', mktime(0, 0, 0, $this->month, 1, $this->year)));
			$and2->where($enddateVar, 'gte', date('Y-m-d', mktime(0, 0, 0, $this->month, 1, $this->year)));
		}

		$filters = array();
		$this->processQueryConditions($q, $filters);

		if(trim($this->getVariable('order', '')) != '')
			$q->setOrder($this->getVariable('order', $dateVar));
		else if(!is_null($dateVar) && !is_null($timeVar))
			$q->setOrder(array($dateVar, $timeVar));
		else
			$q->setOrder($dateVar);

		$q->setLimit($this->getVariable('count', 0), $this->getVariable('start', 0));

		// prepare data
		$ndays = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		$model = array();
		for($i=1; $i <= $ndays ; $i++)
		{
			$model[$i] = array();
		}

		$result = $q->execute();

		foreach($result as $item)
		{
			if(null === $this->enddateAttribute || in_array(trim($item->$enddateVar), array('', '0000-00-00', '0000-00-00 00:00:00')))
			{
				$d = date_parse($item->$dateVar);
				$model[$d['day']][] = $item;
			}
			else
			{
				$d  = strtotime($item->$dateVar);
				$ed = strtotime($item->$enddateVar);
				for($i = 1; $i <= $ndays; $i++)
				{
					$currDay = mktime(0, 0, 0, $this->month, $i, $this->year);
					if($d <= $currDay && $ed >= $currDay) {
						$model[$i][] = $item;
					}
				}
			}
		}

		$this->view->setModel($model);
		$this->view->set('scheme', $this->scheme);
		$this->view->set('calendarDay', $this->day);
		$this->view->set('calendarMonth', $this->month);
		$this->view->set('calendarYear', $this->year);
		$this->view->set('firstCalendarDay', $firstCalendarDay);
		$this->view->set('calendarDays', $calendarDays);
		$this->view->set('currMonthName', $currMonthName);

		return $this->view->show();
	}


}

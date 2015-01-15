<?php
/**
 * Handles a Panel container for Joomla! 2.x
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/form/container/panel.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
Class One_Form_Container_Jpane extends One_Form_Container_Abstract
{
	/**
	 * Class constructor
	 *
	 * @param string $id
	 * @param array $config
	 */
	public function __construct($id, array $config = array())
	{
		parent::__construct( $id, $config );
	}

	/**
	 * Return the allowed options for this container
	 *
	 * @return array
	 */
	protected static function allowedOptions()
	{
		$additional =  array(
								'dir' => 1,
								'lang' => 1,
								'xml:lang' => 1,
								'title' => 2
							);
		return array_merge( One_Form_Container_Abstract::allowedOptions(), $additional );
	}

	/**
	 * Return the allowed events for this container
	 *
	 * @return array
	 */
	protected static function allowedEvents()
	{
		return array(
						'onclick',
						'ondblclick',
						'onmousedown',
						'onmouseup',
						'onmouseover',
						'onmousemove',
						'onmouseout',
						'onkeypress',
						'onkeydown',
						'onkeyup'
					);
	}

	/**
	 * Render the output of the container and add it to the DOM
	 *
	 * @param One_Model $model
	 * @param One_Dom $d
	 */
	protected function _render( $model, One_Dom $d )
	{
		$id     = $this->getID();
		$params = $this->getParametersAsString();
		$events = $this->getEventsAsString();
		$title = $this->getCfg('title');

		$dom = One_Repository::getDom();

		$dom->add('<div class="panel">' . "\n");
		$dom->add('<h3 id="' . $id . '-title" class="pane-toggler title"' . $params . $events . '><a href="javascript:void(0);"><span>' . $title . '</span></a></h3>' . "\n");
		$dom->add('<div class="pane-slider content">');

		foreach($this->getContent() as $content)
			$content->render( $model, $dom );

		$dom->add('</div></div>');

		$d->addDom($dom);
	}

	/**
	 * Overrides PHP's native __toString function
	 *
	 * @return string
	 */
	public function __toString()
	{
		return get_class() . ': ' . $this->getID();
	}
}

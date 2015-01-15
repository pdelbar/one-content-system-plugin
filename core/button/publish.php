<?php
/**
 * Class that shows the "publish" button in the backend
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 **/
class One_Button_Publish extends One_Button
{
	/**
	 * This method renders the "publish" button
	 *
	 * @return string
	 */
	public function render()
	{
		$output = '<td><a href="#" onclick="document.getElementById( \'task\' ).value = \'publish\'; document.getElementById( \''.One_Button::getFormId().'\' ).submit(); "><img src="' . One::getInstance()->getUrl() . '/vendor/images/toolset/' . self::getToolset() . '/publish.png" title="Publish">';

		if( self::showText() )
			$output .= '<br />Publish';

		$output .= '</a></td>';

		return $output;
	}

	/**
	 * This method returns the task that should be used for this tool
	 *
	 * @return string
	 */
	public function getTask()
	{
		return 'publish';
	}

	/**
	 * This method returns the options that should be used for this tool
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return array(
						'noflow' => true,
						'task' => 'publish'
					);
	}
}

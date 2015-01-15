<?php
/**
 * Class that shows the "copy" button in the backend
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 **/
class One_Button_Copy extends One_Button
{
	/**
	 * This method renders the "copy" button
	 *
	 * @return string
	 */
	public function render()
	{
		$output = '<td><a href="#" onclick="document.getElementById( \'task\' ).value = \'copy\'; document.getElementById( \''.One_Button::getFormId().'\' ).submit(); "><img src="' . One::getInstance()->getUrl() . '/vendor/images/toolset/' . self::getToolset() . '/copy.png" title="Copy">';

		if( self::showText() )
			$output .= '<br />Copy';

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
		return 'copy';
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
						'task' => 'copy'
					);
	}
}

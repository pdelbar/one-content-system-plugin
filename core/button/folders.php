<?php
/**
 * Class that shows the "folders" button in the backend
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 **/
class One_Button_Folders extends One_Button
{
	/**
	 * This method renders the "folders" button
	 *
	 * @return string
	 */
	public function render()
	{
		$output = '<td><a href="#" onclick="document.getElementById( \'task\' ).value = \'folders\'; document.getElementById( \''.One_Button::getFormId().'\' ).submit(); "><img src="' . One::getInstance()->getUrl() . '/vendor/images/toolset/' . self::getToolset() . '/backto.png" title="Back To Folders">';

		if( self::showText() )
			$output .= '<br />Back To Folders';

		$output .= '</a></td>';

		return $output;
	}
}
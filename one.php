<?php
/**
 * ---------------------------------------------------------------------------------------------------------
 * 	Everything is content. Content is everything.
 *
 * Copyright (C) 2008 delius bvba. All rights reserved.
 *
 * one|content is free software and is distributed under the GNU General Public License,
 * and as distributed it may include or be derivative of works licensed under the GNU
 * General Public License or other free or open source software licenses.
 * ---------------------------------------------------------------------------------------------------------
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * One Plugin
 */
class plgSystemOne extends JPlugin
{
	public function onAfterInitialise() {
    $this->initializeOne();
    $this->initializeScript();
  }

	protected function initializeOne()
	{
		require_once(dirname(__FILE__).'/core/config.php');
		require_once(dirname(__FILE__).'/core/one.php');

		$app = JFactory::getApplication();
		$application = 'site';
		if(strpos($app->getName(), 'admin') !== false) {
			$application = 'admin';
		}

		One_Config::getInstance($application)
			->setUrl(JURI::root().'/plugins/system/one')
			->setSiterootUrl(JURI::root())
			->setAddressOne('/index.php?option=com_one')
//			->setCustomPath(JPATH_SITE.'/media/one')
			->setUserStore('mysql')
//			->setTemplater('One_Templater_Nano')
			->setLanguage(JFactory::getLanguage()->getTag())
			->setDomType('joomla')
			->setExitOnError($this->params->get('exitOnError'))
			;
		require_once(One_Config::getInstance()->getPath().'/tools.php');

		// set default toolset used in backend
		One_Button::setToolset('joomla');

		define('ONEFORMCHROME',$this->params->get('formChrome', ''));

      if(1 == intval($this->params->get('enableDebug', 0)))
		{
			One_Query::setDebug(true);
		}

		One_Vendor::getInstance()
			->setFilePath(JPATH_SITE.'/plugins/system/one/vendor')
			->setSitePath(JURI::root().'/plugins/system/one/vendor');

		// @deprecated put this in a configuration somewhere instead of using constants
		define('ONECALENDARVIEW', $this->params->get('calendarView', 'month'));
	}

  protected function initializeScript() {
    $tmp = dirname( __FILE__ ) . DS . 'core/script' . DS;
    define( 'ONE_SCRIPT_PATH', $tmp );
    define( 'ONE_SCRIPT_CUSTOM_PATH', JPATH_SITE . DS . 'media' . DS . 'one' . DS . 'script' );

//    require_once( $tmp . 'loader.php' );
//    One_Script_Loader::register();
  }


	public function onAfterRender()
	{
		$buffer = JResponse::getBody();
		// render javascript/css files/codes that need to be included
		$buffer = One_Vendor::getInstance()->renderLoadsOnContent($buffer);

		JResponse::setBody($buffer);
	}
}

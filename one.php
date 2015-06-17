<?php
/**
 * ONEDISCLAIMER
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

require_once "settings.php";

require_once 'core/config.php';
require_once 'core/loader.php';

/**
 * One Plugin
 */
class plgSystemOne extends JPlugin
{
	public function onAfterInitialise() {
        $this->initializeOne();
//        $this->initializeScript();
    }

    protected function initializeOne()
    {
        One_Loader::register();


        $app = JFactory::getApplication();
        $application = 'site';

        if (strpos($app->getName(), 'admin') !== false) {
            $application = 'admin';
        }

        One_Config::getInstance($application)
            ->setUrl(JURI::root().'/plugins/system/one')
            ->setSiterootUrl(JURI::root())
//            ->setSiterootPath( JPATH_SITE)
            ;



        require_once(dirname(__FILE__) . '/core/one.php');


		One_Config::getInstance($application)
			->setAddressOne('/index.php?option=com_one')
//			->setCustomPath(JPATH_SITE.'/media/one')
			->setUserStore('mysql')
			->setTemplater('One_Templater_Nano')
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

//  protected function initializeScript() {
//    $tmp = dirname( __FILE__ ) . DS . 'core/script' . DS;
//    define( 'ONE_SCRIPT_PATH', $tmp );
//    define( 'ONE_SCRIPT_CUSTOM_PATH', JPATH_SITE . DS . 'media' . DS . 'one' . DS . 'script' );

//    require_once( $tmp . 'loader.php' );
//    One_Script_Loader::register();
//  }


	public function onAfterRender()
	{
		$buffer = JResponse::getBody();
		// render javascript/css files/codes that need to be included
		$buffer = One_Vendor::getInstance()->renderLoadsOnContent($buffer);

		JResponse::setBody($buffer);
	}
}

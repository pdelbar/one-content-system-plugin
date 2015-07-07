<?php
  /**
   * ONEDISCLAIMER
   */

// no direct access
  defined('_JEXEC') or die('Restricted access');

  jimport('joomla.plugin.plugin');

  require_once "settings.php";

  require_once 'core/config_tbd.php';
  require_once 'core/loader.php';

  /**
   * One Plugin
   */
  class plgSystemOne extends JPlugin
  {
    public function onAfterInitialise()
    {
      $this->initializeOne();
    }

    protected function initializeOne()
    {
      One_Loader::register();

      // set the application, derived from Joomla status
      $application = 'site';
      $app         = JFactory::getApplication();
      if (strpos($app->getName(), 'admin') !== false) {
        $application = 'admin';
      }
      One_Config::set('app.name', $application);

      // pickup language setting
      $language = JFactory::getLanguage()->getTag();
      One_Config::set('app.language', $language);

      // setup locator tokens
      $locatorTokens = array(
        '%ROOT%' => ONE_LOCATOR_ROOTPATTERN,
        '%APP%'  => One_Config::get('app.name'),
        '%LANG%' => One_Config::get('app.language'),
      );
      One_Config::set('locator.tokens', $locatorTokens);

      // debug behaviour
      One_Config::set('debug.exitOnError', $this->params->get('exitOnError'));

      // special form subfolder to use
      One_Config::set('form.chrome', $this->params->get('formChrome', ''));

      // SETTINGS TO CHANGE / REFACTOR
      One_Config::set('view.templater', 'One_View_Templater_Script');

      // BELOW IS GARBAGE TO CLEAN UP

      One_Config::getInstance($application)
        ->setUrl(JURI::root() . '/plugins/system/one')
        ->setSiterootUrl(JURI::root())
      ;


      require_once(dirname(__FILE__) . '/core/one_tbd.php');


      One_Config::getInstance($application)
        ->setAddressOne('/index.php?option=com_one')
        ->setDomType('joomla');




      require_once(One_Config::getInstance()->getPath() . '/tools_tbd.php');

      // set default toolset used in backend
      One_Button::setToolset('joomla');


      if (1 == intval($this->params->get('enableDebug', 0))) {
        One_Query::setDebug(true);
      }

      One_Vendor::getInstance()
        ->setFilePath(JPATH_SITE . '/plugins/system/one/vendor')
        ->setSitePath(JURI::root() . '/plugins/system/one/vendor');

      // @deprecated put this in a configuration somewhere instead of using constants
      define('ONECALENDARVIEW', $this->params->get('calendarView', 'month'));
    }



    public function onAfterRender()
    {
      $buffer = JResponse::getBody();
      // render javascript/css files/codes that need to be included
      $buffer = One_Vendor::getInstance()->renderLoadsOnContent($buffer);

      JResponse::setBody($buffer);
    }
  }

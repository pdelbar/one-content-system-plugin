<?php
  /**
   * ONEDISCLAIMER
   */

// no direct access
  defined('_JEXEC') or die('Restricted access');

  jimport('joomla.plugin.plugin');

  require_once "settings.php";

  require_once ONE_LIB_PATH . 'core/config_tbd.php';
  require_once ONE_LIB_PATH . 'core/loader.php';

  /**
   * One Plugin
   */
  class plgSystemOne extends JPlugin
  {
    public function onAfterInitialise()
    {
      One_Loader::register();

      $this->initializeOne();
    }

    protected function initializeOne()
    {
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


      // set the templater. You have the choice between One_View_Templater_Script and One_View_Templater_Php,
      // the latter being a standard PHP template hndler
      // *** TODO: needs to load this from plugin parameters
      One_Config::set('view.templater', 'One_View_Templater_Script');


      // debug behaviour
      One_Config::set('debug.exitOnError', $this->params->get('exitOnError'));


      // ---------------------------------------------------------------------------------------------------
      // --- TODO: these should go inside the separate plugin folders, we need a mapper for this usind the locator
      // ---------------------------------------------------------------------------------------------------

      // special form subfolder to use
      // *** TODO: should be inside each extension folder, no ?
      One_Config::set('form.chrome', $this->params->get('formChrome', ''));

      // set default toolset used in backend
      // *** TODO: should be in the support pack for the admin component, to be added to the locator pattern
      // by the admin component (or enabled in the plugin to support the one admin component
      One_Button::setToolset('joomla');

      // set DOM type. Not sure yet whether this is really a cool thing to do. This could override the standard
      // dom setting, and should be oved to the plugin initialiser
      One_Config::set('dom.type','joomla');


      // ---------------------------------------------------------------------------------------------------
      // SETTINGS TO CHANGE / REFACTOR
      // BELOW IS GARBAGE TO CLEAN UP
      // ---------------------------------------------------------------------------------------------------


      if (1 == intval($this->params->get('enableDebug', 0))) {
        One_Query::setDebug(true);
      }

      require_once(dirname(__FILE__) . '/core/one_tbd.php');

      // *** quarantined
//      One_Vendor::getInstance()
//        ->setFilePath(JPATH_SITE . '/plugins/system/one/vendor')
//        ->setSitePath(JURI::root() . '/plugins/system/one/vendor');

    }


    public function onAfterRender()
    {
      $buffer = JResponse::getBody();
      // render javascript/css files/codes that need to be included
      $buffer = One_Vendor::getInstance()->renderLoadsOnContent($buffer);

      JResponse::setBody($buffer);
    }
  }

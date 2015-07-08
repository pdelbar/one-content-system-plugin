<?php

/**
 * Class One_Bootstrap
 *
 * This class initializes one|content according to the required settings for basic paths etc.
 */

require_once 'config.php';
require_once 'loader.php';

class One_Bootstrap
{
    public static function bootstrap($siteRoot,$siteURI)
    {
        One_Loader::register();

        $application = 'site';

        One_Config::getInstance($application)
            ->setUrl($siteRoot.'/plugins/system/one')
            ->setSiterootUrl($siteURI)
//            ->setSiterootPath( $siteRoot)
        ;


        require_once(dirname(__FILE__) . '/one.php');


        One_Config::getInstance($application)
            ->setUserStore('mysql')
            ->setTemplater('One_Templater_Nano')
//        ->setLanguage(JFactory::getLanguage()->getTag())
//        ->setDomType('joomla')
//        ->setExitOnError($this->params->get('exitOnError'))
        ;
        require_once(ONE_LIB_PATH . '/tools.php');

        One_Query::setDebug(true);

//        $tmp = dirname(__FILE__) . DS . 'core/script' . DS;
//        define('ONE_SCRIPT_PATH', $tmp);
//        define('ONE_SCRIPT_CUSTOM_PATH', JPATH_SITE . DS . 'media' . DS . 'one' . DS . 'script');

    }
}

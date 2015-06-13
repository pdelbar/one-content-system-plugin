<?php

class One_Bootstrap
{
    public static function bootstrap($siteRoot,$siteURI)
    {
        require_once(dirname(__FILE__) . '/config.php');

        $application = 'site';

        One_Config::getInstance($application)
            ->setUrl($siteRoot.'/plugins/system/one')
            ->setSiterootUrl($siteURI)
            ->setSiterootPath( $siteRoot)
        ;

        require_once dirname(__FILE__) . '/loader.php';
        One_Loader::register();

        require_once(dirname(__FILE__) . '/one.php');


        One_Config::getInstance($application)
            ->setUserStore('mysql')
            ->setTemplater('One_Templater_Nano')
//        ->setLanguage(JFactory::getLanguage()->getTag())
//        ->setDomType('joomla')
//        ->setExitOnError($this->params->get('exitOnError'))
        ;
        require_once(One_Config::getInstance()->getPath() . '/tools.php');

        One_Query::setDebug(true);

//        $tmp = dirname(__FILE__) . DS . 'core/script' . DS;
//        define('ONE_SCRIPT_PATH', $tmp);
//        define('ONE_SCRIPT_CUSTOM_PATH', JPATH_SITE . DS . 'media' . DS . 'one' . DS . 'script');

    }
}

<?php

/**
 * One_Loader class is being called by the spl autoload function to try and automatically autoload the needed classes
 *
 * ONEDISCLAIMER
 **/
class One_Loader
{

    const ROOTPATTERN = "{media/one/*,plugins/system/one/*}/";

    /**
     * Register One_Loader with the autoloader
     * Enter description here ...
     */
    public static function register()
    {
        if (function_exists('__autoload')) spl_autoload_register('__autoload');
        spl_autoload_register(array('One_Loader', 'load'));
    }

    /**
     * Automatically load the specified classname
     *
     * @param string $className
     */
    public static function load($classname)
    {
        if (substr($classname, 0, 4) == 'One_') {
            $file = self::classnameToDirectory($classname);
            self::_load($file);
        }
    }

    /**
     * Convert the classname to the correct directory structure
     * @param $classname
     */
    protected static function classnameToDirectory($classname)
    {
        $parts = explode('_', $classname); // split on _
        array_shift($parts); // remove the One part

        if (count($parts) == 1)
            $parts[] = $parts[0];

        return strtolower(implode('/', $parts) . '.php');
    }

    /**
     * Look for the right file.
     * If it exists, autoload it and return true, otherwise return false
     *
     * @param string $parts
     * @param string $root
     * @return boolean
     */
    public static function _load($file)
    {
        $path = self::locate($file);
        if ($path === null) return;

        require_once $path;
    }

    /**
     * Locate the filename specified in one of the custom spaces or in core
     * @param $file
     */
    public static function locate($file)
    {
        return self::locateUsing($file, self::ROOTPATTERN);
    }


    /**
     * Locate the filename specified in one of the custom spaces or in core
     * using the pattern specified
     *
     * @param $file
     */

    public static function locateAllUsing($file, $patternStub, $app = null, $language = null)
    {
        $pattern = self::localize($patternStub, $app, $language);
//        $pattern2 = JPATH_SITE . DS . $pattern . $file;
//        echo '<hr>', $pattern2;
        $pattern2 = One_Config::getInstance()->getSiterootpath() . DS . $pattern . $file;
//        echo '<hr>', $pattern2;
//        echo '<br/>Looking for <span style="color: green;"><b>' . $pattern2 . '</b></span>';
//    var_dump( glob($pattern, GLOB_BRACE));
        return glob($pattern2, GLOB_BRACE);
    }

    public static function locateUsing($file, $patternStub, $app = null, $language = null)
    {
        $pattern = self::localize($patternStub, $app, $language);
        $places = self::locateAllUsing($file, $pattern, $app, $language);
        if (count($places)) {
//      echo '<br>FOUND ', $places[0];
            return $places[0];
        }
//    echo '<br><b>NOT</b> FOUND ', $file, ' using ', $patternStub;
        return null;
    }

    /**
     * Replace certain parts of the pattern:
     *
     * REPLACE      BY
     * -------      --------
     * %ROOT%       the root pattern
     * %APP%        the application currently used
     * %LANG%       the current language
     *
     * @param $pattern
     */
    public static function localize($pattern, $app = null, $language = null)
    {

        $app = One_Config::getInstance()->getApplication();

        if (!is_null($language)) {
            $useLang = substr($language, 0, 5);
        } else {
            $useLang = substr(One_Config::getInstance()->getLanguage(), 0, 5);
        }

        return str_replace(array('%ROOT%', '%APP%', '%LANG%'), array(self::ROOTPATTERN, $app, $useLang), $pattern);
    }
}


<?php

  /**
   * One_Locator
   *
   * This class is used to locate files based on their name, in a sequence of locations
   * defined for this particular installation.
   *
   * ONEDISCLAIMER
   **/
  class One_Locator
  {

    /**
     * Simplest format of the locate function: look in the standard sequence of folders
     * as defined by the settings for ONE_LOCATOR_ROOTPATTERN
     *
     * @param $file
     */
    public static function locate($filename)
    {
      return self::locateUsing($filename, ONE_LOCATOR_ROOTPATTERN);
    }

    /**
     * Locate files using a specified folder pattern, typically used when searching for template files
     * @param $filename
     * @param $patternStub
     * @param null $app
     * @param null $language
     * @return null
     */
    public static function locateUsing($filename, $patternStub, $app = null, $language = null)
    {
      $pattern = self::localize($patternStub);
      $places = self::locateAllUsing($filename, $pattern, $app, $language);
      if (count($places) > 0) {
        return $places[0];
      }
      return null;
    }

    /**
     * Specialize the pattern by replacing certain tokens in the provided pattern. These tokens are set in the
     * One_Config object when one|content is bootstrapped. The tokens aer provided as an array, to make the use
     * of the token functionality flexible.
     *
     * REPLACE      BY
     * -------      --------
     * %ROOT%       the root pattern
     * %APP%        the application currently used
     * %LANG%       the current language (is applicable)
     *
     * @param $pattern
     */
    public static function localize($pattern)
    {
      $localized = $pattern;
      foreach (One_Config::get('locator.tokens') as $token => $value) {
        $localized = str_replace($token, $value, $localized);
      }
      return $localized;
    }

    /**
     * Locate the filename specified in one of the custom spaces or in core
     * using the pattern specified
     *
     * @param $file
     */

    public static function locateAllUsing($file, $patternStub, $app = null, $language = null)
    {
      $pattern = self::localize($patternStub);
//        $pattern2 = JPATH_SITE . DS . $pattern . $file;
//        echo '<hr>', $pattern2;
      $pattern2 = ONE_APPLICATION_PATH . DS . $pattern . $file;
//        echo '<hr>', $pattern2;
//        echo '<br/>Looking for <span style="color: green;"><b>' . $pattern2 . '</b></span>';
//    var_dump( glob($pattern, GLOB_BRACE));
      return glob($pattern2, GLOB_BRACE);
    }



  }


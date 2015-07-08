<?php

  /**
   * ONE_PATH points to the parent folder of the one|content core folder (*** is this wording accurate ?)
   */
//  define('ONE_APPLICATION_PATH', JPATH_SITE);

  /**
   * ONE_URI points to the URL where ... uhm ... *** I am not sure
   */
//  define('ONE_URI', JURI::root());

  /**
   * ONE_LIB_PATH points to the location containing the one|content library. This is the parent folder of the core
   * subfolder. It needs to be an absolute path, ending in a /
   *
   * *** the intention is to move this to /media/one/lib
   */
  define('ONE_LIB_PATH', JPATH_SITE . '/plugins/system/one/');

  /**
   * ONE_CUSTOM_PATH points to the location containing the custom extension folders. It needs to be an absolute path,
   * ending in a /
   *
   * *** the intention is to move this to /media/one/custom
   */
  define('ONE_CUSTOM_PATH', JPATH_SITE . '/media/one/');

  /**
   * ONE_LOCATOR_ROOTPATTERN provides the default sequence of folders to search for files. It gives preference to the
   * custom folder, which makes it possible to override standard classes (at your own peril).
   */
  define('ONE_LOCATOR_ROOTPATTERN', '{' . ONE_CUSTOM_PATH . '*,' . ONE_LIB_PATH . '*}/');

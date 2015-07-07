<?php

  /**
   * ONE_PATH points to the parent folder of the one|content core folder (*** is this wording accurate ?)
   */
  define('ONE_APPLICATION_PATH', JPATH_SITE);

  /**
   * ONE_URI points to the URL where ... uhm ... *** I am not sure
   */
  define('ONE_URI', JURI::root());

  /**
   * ONE_LOCATOR_ROOTPATTERN provides the default sequence of folders to search for files
   */
  define( 'ONE_LOCATOR_ROOTPATTERN', '{media/one/*,plugins/system/one/*}/');

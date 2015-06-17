<?php
/**
 * This is a template nano initiator file for you to build on. You will need to place this OUTSIDE the nano directory since it is specific to your
 * use of the library.
 */

        if( !defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );

        /**
         * This is the path to the nano directory (external reference to the nano subdirectory in the nscript SVN)
         */
//        define( 'ONE_SCRIPT_PATH', dirname(__FILE__) . DS . 'nano' );

        /**
         * This is the path to your custom nano folder, typically places under a custom directory
         */
//        define( 'ONE_SCRIPT_CUSTOM_PATH', dirname(__FILE__) . '/../' . 'custom' . DS . 'nano' );
        //define( 'ONE_SCRIPT_CUSTOM_PATH', dirname(__FILE__) . '/' . 'custom' . DS . 'nano' );

        /**
         * This initiates the autoloader so all classes can be found
         */
        require_once(dirname(__FILE__) . DS . 'tools' . DS . 'autoload.php');

<?php

  require_once "registry/registry.php";

  /**
   * One_Config is a singleton class containing a One_Registry for all values we want to store for this
   * configuration
   *
   * ONEDISCLAIMER
   **/
  class One_Config
  {
    /**
     * @var One_Registry instance keeping the config information
     */
    private static $registry;

    /**
     * Get
     */
    public static function get($key)
    {
      if (empty(self::$registry)) {
        self::$registry = new One_Registry();
      }
      return self::$registry->get($key);
    }

    public static function set($key,$value)
    {
      if (empty(self::$registry)) {
        self::$registry = new One_Registry();
      }
      return self::$registry->set($key,$value);
    }

    //----------------------

    /**
     * @var One_Config
     */
    static $_instance;

    /**
     * @var string String you would put behind the root-url to address One (E.G.: for Joomla!: /index.php?option=com_one&tmpl=blank)
     */
//    protected $_addressOne;

    /**
     * Default DOM-type to be used
     * @var unknown_type
     */
    protected $domType;

    /**
     * Protected constructor which mainly defines the current application
     * @param string $application
     */
    protected function __construct($application = 'site')
    {
    }



    /**
     * Get a One instance
     * @return One_Config
     */
    public static function &getInstance($application = 'site')
    {
      if (empty(self::$_instance)) {
        self::$_instance = new One_Config($application);
        echo "(creating new instance of One_Config)";
      }

      return self::$_instance;
    }



    /**
     * Get the siteroot-url
     * @return string
     */
    public function getSiterootUrl()
    {
      throw new One_Exception_Deprecated('Someone is calling One_Config->getSiterootUrl(), which probably means that the caller
      is trying to load a file from inside the one library using a web URL -- not so happy about that. Please
      consider a different approach.');
//      return $this->_siterooturl;
    }

    /**
     * Get the url
     * @return string
     */
    public function getUrl()
    {
      throw new One_Exception_Deprecated('Someone is calling One_Config->getUrl(), which probably means that the caller
      is trying to load a file from inside the one library using a web URL -- not so happy about that. Please
      consider a different approach.');
//      return $this->_url;
    }






    public function getAddressOne()
    {
      throw new One_Exception_Deprecated('Please do not use getAddressOne ...');
//      return $this->_addressOne;
    }


  }

<?php

  require_once "tools/hash.php";

  /**
   * One_Config is a singleton class containing a One_Tools_Hash for all values we want to store for this
   * configuration
   *
   * ONEDISCLAIMER
   **/
  class One_Config
  {
    /**
     * @var One_Tools_Hash instance keeping the config information
     */
    private static $_hash;

    /**
     * Get
     */
    public static function get($key)
    {
      if (empty(self::$_hash)) {
        self::$_hash = new One_Tools_Hash();
      }
      return self::$_hash->get($key);
    }

    public static function set($key,$value)
    {
      if (empty(self::$_hash)) {
        self::$_hash = new One_Tools_Hash();
      }
      return self::$_hash->set($key,$value);
    }

    //----------------------

    /**
     * @var One_Config
     */
    static $_instance;

    /**
     * @var string
     */
    protected $_custompath;

    /**
     * @var string
     */
    protected $_siterootpath;

    /**
     * @var string
     */
    protected $_url;

    /**
     * @var string
     */
    protected $_siterooturl;

    /**
     * @var string String you would put behind the root-url to address One (E.G.: for Joomla!: /index.php?option=com_one&tmpl=blank)
     */
    protected $_addressOne;

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
     * Get the path of the One library
     * @return string
     */
    public function getPath()
    {
      return dirname(__FILE__);
    }

    /**
     * Get the siteroot-url
     * @return string
     */
    public function getSiterootUrl()
    {
      return $this->_siterooturl;
    }

    /**
     * Set the siteroot-url
     * @param string $url
     * @return One_Config
     */
    public function setSiterootUrl($url)
    {
      $this->_siterooturl = $url;
      echo "(setting siterooturl to $url)";
      return $this;
    }

    /**
     * Get the url
     * @return string
     */
    public function getUrl()
    {
      return $this->_url;
    }

    /**
     * Set the url
     * @param string $url
     * @return One_Config
     */
    public function setUrl($url)
    {
      $this->_url = $url;
      return $this;
    }





    public function getDomType()
    {
      return $this->domType;
    }

    public function setDomType($type)
    {
      $this->domType = ucfirst(strtolower($type));
      return $this;
    }

    public function getAddressOne()
    {
      return $this->_addressOne;
    }

    public function setAddressOne($uri)
    {
      $this->_addressOne = $uri;
      return $this;
    }

  }

<?php

  /**
   * One_Tools_Hash
   *
   * Utility class to represent hierarchically structured data (trees, arrays of arrays, ...)
   * such as configurations, REQUEST, ... capable of being instantiated in various ways
   *
   * ONEDISCLAIMER
   */
  class One_Tools_Hash
  {

    protected $_data;

    public function __construct($source = null)
    {
      $this->_data = array();
      if ($source) {
        $this->initializeFrom($source);
      }
    }

    /**
     * Fills the hash with a particular set of contents (such as INI file, array, ...)
     *
     * @param $source  string in format protocol:path
     */
    public function initializeFrom($source = '')
    {
      list($protocol, $path) = explode(':', $source, 2);
      switch ($protocol) {
        case 'ini' :
          $this->loadFromIni($path);
          break;
        case 'request' :
          $this->loadFromArray($_REQUEST);
          break;
        case 'post' :
          $this->loadFromArray($_POST);
          break;
        case 'get' :
          $this->loadFromArray($_GET);
          break;
        case 'session' :
          $this->loadFromArray($_SESSION);
          break;
        default:
          throw new One_Exception('One_Tools_Hash error: unknown protocol ' . $protocol);
          break;
      }
    }

    /**
     * Load from an .ini file (using sections)
     *
     * @param $path
     */
    private function loadFromIni($path)
    {
      $items = parse_ini_file($path, true);
      if ($items === false) {
        throw new One_Exception('One_Tools_Hash error: error loading INI file ' . $path);
      }
      $this->_data = $items;
    }

    /**
     * Initialize from an existing array
     *
     * @param $data
     */
    private function loadFromArray($data)
    {
      $this->data = $data;
    }

    /**
     * Access the hashtable and return the value for the resource locator (aaa.bbb.ccc)
     *
     * @param $resourceLocator
     * @param null $default
     * @return null
     */
    public function get($resourceLocator, $default = null)
    {
      $parts = preg_split("/\./", $resourceLocator);
      if (count($parts) == 0) {
        return $default;
      }
      return $this->getRecursive($this->_data, $parts, $default);
    }

    /**
     * Search recursively in array structure
     *
     * @param $data
     * @param $rlparts
     * @param null $default
     * @return null
     */
    private function getRecursive($data, $parts, $default = null)
    {
      $part = array_shift($parts);
      if (!array_key_exists($part, $data)) {
        return $default;
      }
      $data = $data[$part];
      if (count($parts) == 0) {
        return $data;
      }
      return $this->getRecursive($data, $parts, $default);
    }

    /**
     * Set a key to a value
     *
     * @param $resourceLocator
     * @param null $default
     * @return null
     */
    public function set($resourceLocator, $value)
    {
      $parts = preg_split("/\./", $resourceLocator);
      if (count($parts) == 0) {
        return null;
      }
      return $this->setRecursive($this->_data, $parts, $value);
    }

    /**
     * Set recursively in array structure
     *
     * @param $data
     * @param $rlparts
     * @param null $default
     * @return null
     */
    private function setRecursive(&$data, $parts, $value)
    {
      $part = array_shift($parts);
      if (empty($parts)) {
        $data[$part] = $value;
        return true;
      }

      if (!array_key_exists($part, $data)) {
        $data[$part] = array();
      }
      if (!is_array($data[$part])) {
        $data[$part] = array();
      }

      return $this->setRecursive($data[$part], $parts, $value);
    }
  }

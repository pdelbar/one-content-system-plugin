<?php
require_once dirname(__FILE__).'/loader.php';
One_Loader::register();

/**
 * @deprecated
 */
if(!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }

/**
 * One
 *
 * @author Mathias Verraes <mathias@delius.be>
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage One
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One
{
	/**
	 * @var One
	 */
	static $_instance;

	/**
	 * @var string
	 */
	protected $_application;

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
	 * @var string
	 */
	protected $_userstore;

	/**
	 * @var string
	 */
	protected $_language = 'en-gb';

	/**
	 * Default DOM-type to be used
	 * @var unknown_type
	 */
	protected $domType;

	protected $exitOnError = true;

	/**
	 * Protected constructor which mainly defines the current application
	 * @param string $application
	 */
	protected function __construct($application = 'site')
	{
		$this->setApplication($application);
	}

	public function setApplication($application = 'site')
	{
		$this->_application = strtolower($application);
		return $this;
	}

	public function getApplication()
	{
		return $this->_application;
	}

	/**
	 * Get a One instance
	 * @return One
	 */
	public static function getInstance($application = 'site')
	{
		if(empty(self::$_instance)) {
			self::$_instance = new One($application);
		}

		return self::$_instance;
	}

	/**
	 * Remove the instance
	 * @return void
	 */
	public static function unsetInstance()
	{
		self::$_instance = null;
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
	 * Get the path of the custom files
	 * @return string
	 */
//	public function getCustomPath()
//	{
//		return $this->_custompath;
//	}

	/**
	 * Set the path of the custom files
	 * @param string $path Path
	 * @return One
	 */
//	public function setCustomPath($path)
//	{
//		$this->_custompath = $path;
//		return $this;
//	}


  public function locate($filename) {
    return One_Loader::locate($filename);
  }
	/**
	 * Get the siteroot-path
	 * @return string
	 */
	public function getSiterootPath()
	{
		return $this->_siterootpath;
	}

	/**
	 * Set the siteroot-path
	 * @param string $path
	 * @return One
	 */
	public function setSiterootPath($path)
	{
		$this->_siterootpath = $path;
		return $this;
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
	 * @return One
	 */
	public function setSiterootUrl($url)
	{
		$this->_siterooturl = $url;
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
	 * @return One
	 */
	public function setUrl($url)
	{
		$this->_url = $url;
		return $this;
	}

	/**
	 * Get the store that contains the user
	 * @return string
	 */
	public function getUserStore()
	{
		return $this->_userstore;
	}

	/**
	 * Set the store that contains the user
	 * @param string $user
	 * @return One
	 */
	public function setUserStore($store)
	{
		$this->_userstore = $store;
		return $this;
	}

	/**
	 * Set the classname for the templaterAdapter
	 * Previously object, but caused wrong parsing
	 * @param string $adapter
	 */
  private $_templater;

	public function setTemplater($adapter)
	{
		$this->_templater = $adapter;
		return $this;
	}

	public function getTemplater()
	{
		return $this->_templater;
	}

	public function setLanguage($language)
	{
		$this->_language = $language;
		return $this;
	}

	public function getLanguage()
	{
		return $this->_language;
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

	public function setExitOnError($exit = 1)
	{
		if(intval($exit) == 1) {
			$this->exitOnError = true;
		}
		else {
			$this->exitOnError = false;
		}

		return $this;
	}

	public function exitOnError()
	{
		return $this->exitOnError;
	}
}

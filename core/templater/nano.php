<?php
/**
 * Class that parses nano template files
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @subpackage Template
 * @filesource one/lib/template/template.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Templater_Nano extends One_Templater_Abstract
{
	/**
	 * NanoScript object used to parse the template
	 * @var nScript
	 */
	protected $nScript = NULL;

	/**
	 * Class constructor
	 * @param array $searchpaths
	 */
	public function __construct( array $searchpaths = array(), $setSearchpaths = true )
	{
		parent::__construct( $searchpaths, $setSearchpaths );
		$this->nScript = new One_Script();
	}

	public function setFile( $filename )
	{
		parent::setFile( $filename );

		if($this->nScript->isError()) {
			throw new One_Exception($this->nScript->error);
		}
	}

	/**
	 * Parse the template or if $section is set and the section exists, parse the specified section
	 * @param string $section
	 */
	public function parse( $section = NULL )
	{
		$oldSearchpath = One_Script_Factory::getSearchPath();
		One_Script_Factory::clearSearchpath();
		One_Script_Factory::setSearchPath( $this->getSearchpath() );
		if( $this->useFile() )
		{
			$this->nScript->load( $this->getFile() );
			$this->nScript->select( $section );
			if( !$this->nScript->isError() ) {
				$output = $this->nScript->execute( $this->getData() );
			}
		}
		else
		{
			$this->nScript->select( $section );
			$output = $this->nScript->executeString( $this->getContent(), $this->getData() );
		}

		One_Script_Factory::clearSearchpath();
		One_Script_Factory::setSearchPath($oldSearchpath);
		One_Script_Content_Factory::$nsContentCache = array();

		if($this->nScript->isError()) {
			throw new One_Exception($this->nScript->error);
		}

		return trim($output);
	}

	/**
     * (non-PHPdoc)
     * @see One_Template_Adapter_Abstract::formatDataKeys()
     */
	protected function formatDataKeys(array $data)
	{
		foreach($data as $key => $val)
		{
			$oriKey = $key;
			$key = $this->formatDataKey($key);

			if($key != $oriKey)
			{
				$data[$key] = $data[$oriKey];
				unset($data[$oriKey]);
			}
		}

		return $data;
	}


	/**
     * (non-PHPdoc)
     * @see One_Template_Adapter_Abstract::formatDataKey()
     */
	protected function formatDataKey($key)
	{
		if(function_exists($key))
		{
			$oriKey = $key;
			do {
				$key = '_'.$key;
			} while(array_key_exists($key, $this->getData()));

			One_Log::getInstance('message')->log(array('message' => 'The key "'.$oriKey.'" has been changed to "'.$key.'"'));
		}

		return $key;
	}
}
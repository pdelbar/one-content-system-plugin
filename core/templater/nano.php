<?php

/**
 * The templater concept is designed to make it possible to use different template languages and tools inside One. This is the renderer supporting One_Script.
 *
 * @TODO review this file and clean up historical code/comments
 * ONEDISCLAIMER
 **/
class One_Templater_Nano extends One_Templater_Abstract {
  /**
   * NanoScript object used to parse the template
   * @var nScript
   */
  protected $script = NULL;

  /**
   * Class constructor
   * @param array $searchpaths
   */
  public function __construct( $searchPath, $setSearchpaths = true) {
    parent::__construct($searchPath, $setSearchpaths);
    $this->script = new One_Script();
  }

  public function setFile($filename) {
    parent::setFile($filename);

    if ($this->script->isError()) {
      throw new One_Exception($this->script->error);
    }
  }

  /**
   * Parse the template or if $section is set and the section exists, parse the specified section
   * @param string $section
   */
  public function parse($section = NULL) {
    $oldSearchpath = One_Script_Factory::getSearchPath();
//    One_Script_Factory::clearSearchpath();
    One_Script_Factory::setSearchPath($this->getSearchPath());
    if ($this->useFile()) {
      $this->script->load($this->getFile());
      $this->script->select($section);
      if (!$this->script->isError()) {
        $output = $this->script->execute($this->getData());
      }
    } else {
      $this->script->select($section);
      $output = $this->script->executeString($this->getContent(), $this->getData());
    }

//    One_Script_Factory::clearSearchpath();
    One_Script_Factory::setSearchPath($oldSearchpath);
    One_Script_Content_Factory::$nsContentCache = array();

    if ($this->script->isError()) {
      throw new One_Exception($this->script->error);
    }

    return trim($output);
  }

  /**
   * (non-PHPdoc)
   * @see One_Template_Adapter_Abstract::formatDataKeys()
   */
  protected function formatDataKeys(array $data) {
    foreach ($data as $key => $val) {
      $oriKey = $key;
      $key = $this->formatDataKey($key);

      if ($key != $oriKey) {
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
  protected function formatDataKey($key) {
    if (function_exists($key)) {
      $oriKey = $key;
      do {
        $key = '_' . $key;
      } while (array_key_exists($key, $this->getData()));

      One_Log::getInstance('message')->log(array('message' => 'The key "' . $oriKey . '" has been changed to "' . $key . '"'));
    }

    return $key;
  }
}
<?php

/**
 * Class that handles the output of the model views
 *
 * ONEDISCLAIMER
 **/
class One_View {
  /**
   * @var string Name of the view
   */
  public $name;

  /**
   * @var mixed Can be either One_Model or a list of One_Models
   */
  public $model;

  /**
   * Configuration options for the view
   * @var array
   */
  protected $options;

  protected $script;

  /**
   * Class constructor
   *
   * @param mixed $modelThing
   * @param string $viewName
   */
  public function   __construct($modelThing, $viewName = 'default', $language = null, array $options = array()) {
    $this->name    = $viewName;
    $this->options = $options;

    if ( $modelThing instanceof One_Scheme ) {
      $schemeName = $modelThing->getName();
    } elseif ( $modelThing instanceof One_Model ) {
      $schemeName = $modelThing->getSchemeName();
    } elseif ( is_array($modelThing) && count($modelThing) > 0 ) {
      $schemeName = $modelThing[0]->getSchemeName();
    } else {
      $schemeName = $modelThing;
    }

    $this->setDefaultViewSearchPaths($schemeName);

    $type         = isset($this->options['type']) ? trim($this->options['type']) : 'html';
    $this->script = new One_Script();
    $this->script->load($viewName . '.' . $type);

    if ( $this->script->error ) {
      One_Script_Factory::popSearchPath();
      throw new One_Exception("Could not load view '" . $viewName . "' for scheme '" . $schemeName . "' : " . $this->script->error);
    }

    //PD23SEP09: added setModel, seems like a logical thing to do here
    $this->setModel($modelThing);
  }

  /**
   * Set the One_Script search path according to scheme
   *
   * The order to look is
   * 1) SPACE/views/APP/SCHEME/LANG
   * 2) SPACE/views/APP/SCHEME/
   * 3) SPACE/views/APP/LANG
   * 4) SPACE/views/APP/
   * 3) SPACE/views/default/LANG
   * 4) SPACE/views/default/
   *
   * @param string $schemeName
   */
  public function setDefaultViewSearchPaths($schemeName = '') {

    $pattern = "%ROOT%/views/"
      . "{" . ($schemeName != '' ? "%APP%/$schemeName," : "") . "%APP%,default}" . DS
      . "{%LANG%/,}";

    One_Script_Factory::pushSearchPath($pattern);
  }

  /**
   * Set the model for the view
   *
   * @param mixed $model Can be either One_Model or list of One_Models
   */
  public function setModel(&$model) {
    $this->model = $model;
    $this->set('model', $model);
  }

  /**
   * Set a variable that can be used in the view
   *
   * @param string $parameter
   * @param mixed $data
   */
  public function set($parameter, $data) {
    $this->script->set($parameter, $data);
  }

  /**
   * Set an array of  variables that can be used in the view
   *
   * @param mixed $data
   */
  public function setAll($data) {
    if ( count($data) > 0 ) foreach ($data as $k => $v) {
      $this->set($k, $v);
    }
  }

  /**
   * Render the output of the view.
   * If a section is given, only render the given section
   *
   * @param string $section
   * @return string
   */
  public function show($section = null) {
    $result = $this->script->execute($section);

    if ( $this->script->error ) {
      One_Script_Factory::popSearchPath();
      throw new One_Exception($this->script->error);
    }

    One_Script_Factory::popSearchPath();
    return $result;
  }

}

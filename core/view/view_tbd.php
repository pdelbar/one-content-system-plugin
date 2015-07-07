<?php

  /**
   * A One_View handles all presentation aspects for a scheme. It's the V in MVC.
   *
   * ONEDISCLAIMER
   **/
  class One_View
  {
    /**
     * @var string Name of the view
     */
    public $name;

    /**
     * @var mixed Can be either One_Model or a list of One_Models
     */
    public $model;

    /**
     * @var the One_View_Templater instance that renders this view
     */
    protected $templater;

    /**
     * Configuration options for the view
     *
     * @var array
     */
    protected $options;

    /**
     * Class constructor, with different prototypes:
     *   new One_View( One_Scheme, ...)
     *   new One_View( 'schemename', ...)
     *   new One_View( One_Model, ...)
     *   new One_View( [One_Model ...], ...)
     *
     * @param mixed $modelThing
     * @param string $viewName
     */
    public function __construct($modelThing, $viewName = 'default', $language = NULL, array $options = array())
    {
      $this->options = $options;
      $this->name    = $viewName;

      // *** is this used ? for what? search for this parameter in the constructor (also in nanoscript views?)
      $type = isset($this->options['type']) ? trim($this->options['type']) : 'html';

      // which scheme are we rendering for ?
      if ($modelThing instanceof One_Scheme) {
        $schemeName = $modelThing->getName();
      }
      elseif ($modelThing instanceof One_Model) {
        $schemeName = $modelThing->getSchemeName();
      }
      elseif (is_array($modelThing) && count($modelThing) > 0) {
        $schemeName = $modelThing[0]->getSchemeName();
      }
      else {
        $schemeName = $modelThing;
      }

      // setup templater
      $this->templater = One_Repository::getTemplater();

      // *** this sectin is hard to follow. Should the search path not belong to the templater ?
      $this->setDefaultViewSearchPath($schemeName, $language);
      $this->templater->setFile($viewName . '.' . $type);
      if ($this->templater->hasError()) {
        throw new One_Exception("Could not load view '" . $viewName . "' for scheme '" . $schemeName . "' : " . $this->templater->getError());
      }

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
    public function setDefaultViewSearchPath($schemeName = '', $language = NULL)
    {
      $pattern = "%ROOT%/views/"
        . "{" . ($schemeName != '' ? "%APP%/$schemeName," : "") . "%APP%,default}" . DS
        . "{%LANG%/,}";

      $this->templater->setSearchPath($pattern);
    }

    /**
     * Set the model for the view
     *
     * @param mixed $model Can be either One_Model or list of One_Models
     */
    public function setModel(&$model)
    {
      $this->model = $model;
      $this->templater->addData('model', $model);
    }

    /**
     * Set a variable that can be used in the view
     *
     * @param string $parameter
     * @param mixed $data
     */
    public function set($parameter, $data)
    {
      $this->templater->addData($parameter, $data);
    }

    /**
     * Set an array of  variables that can be used in the view
     *
     * @param mixed $data
     */
    public function setAll($data)
    {
      if (count($data) > 0) {
        foreach ($data as $k => $v) {
          $this->set($k, $v);
        }
      }
    }

    /**
     * Render the output of the view.
     * If a section is given, only render the given section
     *
     * @param string $section
     * @return string
     */
    public function show($section = NULL)
    {
      $result = $this->templater->parse($section);

      if ($this->templater->hasError()) {
        throw new One_Exception($this->templater->getError());
      }

      return $result;
    }

    public function addSearchpath($path)
    {
      return $this->templater->addSearchpath($path);
    }

    public function getSearchpath()
    {
      return $this->templater->getSearchpath();
    }
  }

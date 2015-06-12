<?php

/**
 * Handles a form container
 *
 * @TODO review this file and clean up historical code/comments
 * ONEDISCLAIMER
 **/
class One_Form_Container_Form extends One_Form_Container_Abstract {
  /**
   * @var string The form method
   */
  protected $_method;

  /**
   * @var string The form action
   */
  protected $_action;

  /**
   * @var array List of errors, errors generated by faulty input on submission
   */
  protected $_errors = array();

  protected $_redirects = array();

  /**
   * Class constructor
   *
   * @param string $id
   * @param string $action
   * @param string $method
   * @param array $config
   */
  public function __construct($id, $action = '', $method = 'get', array $config = array()) {
    parent::__construct($id, $config);
    $this->setAction($action);
    $this->setMethod($method);

    if (isset($config['redirects']) && is_array($config['redirects'])) {
      $this->_redirects = $config['redirects'];
    }

    $this->addWidget(new One_Form_Widget_Token());

    $captcha = $this->getCfg('captcha');
    if ($captcha) {
      // @todo: should already be added before we call the render() function...
      $this->addWidget(new One_Form_Widget_Captcha());
    }
  }

  /**
   * Return the allowed options for this container
   *
   * @return array
   */
  protected static function allowedOptions() {
    $additional = array(
      'dir'                => 1,
      'lang'               => 1,
      'xml:lang'           => 1,
      'accept'             => 1,
      'accept-charset'     => 1,
      'enctype'            => 1,
      'target'             => 1,
      'widgetsInContainer' => 2,
      'captcha'            => 2,
      'type'               => 2
    );
    return array_merge(One_Form_Container_Abstract::allowedOptions(), $additional);
  }

  /**
   * Return the allowed events for this container
   *
   * @return array
   */
  protected static function allowedEvents() {
    return array(
      'onsubmit',
      'onreset',
      'onclick',
      'ondblclick',
      'onmousedown',
      'onmouseup',
      'onmouseover',
      'onmousemove',
      'onmouseout',
      'onkeypress',
      'onkeydown',
      'onkeyup'
    );
  }

  /**
   * Render the output of the container and add it to the DOM
   *
   * @param One_Model $model
   * @param One_Dom $d
   */
  protected function _render($model, One_Dom $d) {
    $this->renderStart($model, $d);
    $this->renderBody($model, $d);
    $this->renderEnd($model, $d);
  }

  /**
   * Render the form start and add it to the DOM
   *
   * @param One_Model $model
   * @param One_Dom $d
   */
  public function renderStart($model, One_Dom $d) {
    $id = $this->getID();
    $method = $this->getMethod();
    $params = $this->getParametersAsString();
    $events = $this->getEventsAsString();

    $dom = One_Repository::getDom();

    $dom->add('<form name="' . $id . '" id="' . $id . '" action="' . $this->_action . '" method="' . $method . '"' . $params . $events . '>' . "\n");
    $d->addDom($dom);
  }

  /**
   * Render the form body (all widgets and containers) and add it to the DOM
   *
   * @param One_Model $model
   * @param One_Dom $d
   */
  public function renderBody($model, One_Dom $d) {
    $dom = One_Repository::getDom();
    foreach ($this->getContent() as $content) {
      $content->render($model, $dom);
    }
    $d->addDom($dom);
  }

  /**
   * Render the form ending and add it to the DOM
   *
   * @param One_Model $model
   * @param One_Dom $d
   */
  public function renderEnd($model, One_Dom $d) {
    $dom = One_Repository::getDom();
    $dom->add('</form>' . "\n");
    $d->addDom($dom);
  }

  /**
   * Validate whether the form has been submitted correctly
   *
   * @param boolean $root
   * @param One_Form_Container_Abstract $container
   * @return boolean
   */
  public function validate($root = true, $container = NULL) {
    $checksOut = true;
    $oc = new One_Context();
    $scheme = $oc->get('scheme');
    $schemes = One::meta('schemes');

    if (in_array($scheme, $schemes)) {
      $scheme = One_Repository::getScheme($scheme);
      $use = ($root) ? $this : $container;

      foreach ($use->getContent() as $widget) {
        if ($widget instanceof One_Form_Widget_Abstract) {
          $attr = $scheme->getAttribute($widget->getName());
          if ($attr instanceof One_Scheme_Attribute) {
            $type = strtolower(str_replace('One_Type', '', get_class($attr->getType())));
            $widget->setCfg('type', $type);
          }

          if (!$widget->validate()) {
            $checksOut = false;
            $this->_errors[$widget->getName()]['error'] = $widget->getErrors();
            $this->_errors[$widget->getName()]['label'] = $widget->getLabel();
          }
        } else if ($widget instanceof One_Form_Container_Abstract) {
          if (!self::validate(false, $widget)) {
            $checksOut = false;
          }
        }
      }

      return $checksOut;
    } else
      return false;
  }

  /**
   * Recursive function to find a specific widget/container in the form
   *
   * @param string $id string ID to look for
   * @param One_Form_Container_Abstract $in Mixed instance of One_Form_Container_Abstract to look in
   * @return mixed returns One_Form_Widget_Abstract/One_Form_Container_Abstract if the id was found, NULL if not found
   */
  public function findPart($id, $in = NULL) {
    $part = NULL;

    $search = $id;
    if (preg_match('/:/', $search) > 0) // replace roles by a proper id
      $search = 'r__' . preg_replace('/:/', '_', $search);

    if (is_null($in))
      $searchContent = $this->getContent();
    else
      $searchContent = $in->getContent();

    foreach ($searchContent as $content) {
      if ($content->getID() == $search) {
        $part = $content;
        break;
      } else if ($content instanceof One_Form_Container_Abstract) {
        $part = $this->findPart($search, $content);
        if (!is_null($part))
          break;
      }
    }

    return $part;
  }

  /**
   * Overrides PHP's native __toString function
   *
   * @return string
   */
  public function __toString() {
    return get_class() . ': ' . $this->getID();
  }

  /**
   * Get the form's action
   *
   * @return string
   */
  public function getAction() {
    return $this->_action;
  }

  /**
   * Get the form's method
   *
   * @return string
   */
  public function getMethod() {
    return $this->_method;
  }

  /**
   * Return any possible errors
   *
   * @return array
   */
  public function getErrors() {
    return $this->_errors;
  }

  /**
   * Set the form's action
   *
   * @return string
   */
  private function setAction($action) {
    $this->_action = $action;
  }

  /**
   * Set the form's method
   *
   * @return string
   */
  private function setMethod($method) {
    switch (strtolower($method)) {
      case 'post':
        $this->_method = 'post';
        break;
      case 'get':
      default:
        $this->_method = 'get';
        break;
    }
  }

  public function getRedirects() {
    return $this->_redirects;
  }
}

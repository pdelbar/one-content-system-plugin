<?php

/**
 * Behavior that let's you override the One_Model class for the scheme
 *
 * ONEDISCLAIMER
 **/
class One_Behavior_Scheme_Class extends One_Behavior_Scheme {
  /**
   * Return the name of the behaviour
   *
   * @return string
   */
  public function getName() {
    return 'class';
  }

  /**
   * Returns the class that overrides the default One_Model
   * @param One_Scheme $scheme
   * @return One_Model
   */
  public function onCreateModel(One_Scheme $scheme) {
    $className = 'One_Model_' . ucFirst($scheme->getName());
    $x         = new $className($scheme);
    return $x;
  }
}

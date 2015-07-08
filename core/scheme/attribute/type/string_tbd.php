<?php

/**
 * Treats a scheme-attribute as a string
 *
 * @TODO review this file and clean up historical code/comments
 * @subpackage Type
 * ONEDISCLAIMER
 **/
class One_Type_String extends One_Type {
  /**
   * Get the name of the attribute
   *
   * @return string
   */
  public function getName() {
    return "string";
  }

  /**
   * Returns the value as a string value
   *
   * @return string
   */
  public function toString($value) {
    return '"' . $value . '"';
  }
}

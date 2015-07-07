<?php

/**
 * The One_Behavior abstract class implements the basic behavior for a mixin behavior
 * such as performing pre/postload actions.
 *
 * ONEDISCLAIMER
 **/
abstract class One_Behavior {
  /**
   * Method that returns the name of the behavior
   *
   * @return string
   * @abstract
   */
  abstract public function getName();
}

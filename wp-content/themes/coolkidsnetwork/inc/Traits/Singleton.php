<?php

/**
 * Trait Singleton.
 *
 * Provides a method to ensure only one instance of a class is created.
 * 
 * @package Cool Kids Network
 */

namespace CoolKidsNetwork\Traits;

/**
 * Trait Singleton.
 *
 * Provides a method to ensure only one instance of a class is created.
 */
trait Singleton {
  private static $instance = null;

  /**
   * Constructor for the Singleton trait.
   *
   * This method is protected to prevent direct instantiation.
   */
  protected function __construct() {
  }

  /**
   * Prevent cloning of the instance.
   *
   * @throws \Exception Always throws an exception.
   */
  protected function __clone() {
  }

  /**
   * Prevent unserializing of the instance.
   *
   * @throws \Exception Always throws an exception.
   */
  public function __wakeup() {
    throw new \Exception("Cannot unserialize singleton");
  }

  /**
   * Retrieves the instance of the class.
   *
   * @return static The instance of the class.
   */
  public static function get_instance() {
    if (null === self::$instance) {
      self::$instance = new static();
    }

    return self::$instance;
  }
}

<?php

namespace CoolKidsNetwork\Traits;

trait Singleton
{
  private static $instance = null;

  protected function __construct()
  {
    // Protected constructor to prevent direct creation
  }

  public static function get_instance()
  {
    if (null === self::$instance) {
      self::$instance = new static();
    }
    return self::$instance;
  }
}

<?php

namespace CoolKidsNetwork\Traits;

trait Singleton
{
  private static $instance = null;

  protected function __construct() {}

  public static function get_instance()
  {
    if (null === self::$instance) {
      self::$instance = new static();
    }
    return self::$instance;
  }
}

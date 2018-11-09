<?php
namespace BSFP\C;

use ArrayIterator;
use IteratorAggregate;
use stdClass;

class Bag implements IteratorAggregate
{
  private $properties;

  public function __construct(array $properties)
  {
    $this->properties = $properties; 
  }

  /**
   * Get object parameter
   *
   * @param $key String
   * @param $default mixed
   * @return mixed
   */
  public function get(string $key, $default = null)
  {
    return $this->properties[$key] ?? $default;
  }

  /**
   * Get object parameter
   *
   * @param $key String
   * @param $default mixed
   * @return mixed
   */
  public function __get($key)
  {
    return $this->properties[$key] ?? null;
  }

  /**
   * Has object parameter
   *
   * @param $key String
   * @return bool
   */
  public function has(string $key): bool
  {
    return isset($this->properties[$key]);
  }

  /**
   * @return ArrayIterator
   */
  public function getIterator()
  {
    return new ArrayIterator($this->properties);
  }
}

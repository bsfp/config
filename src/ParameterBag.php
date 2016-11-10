<?php
namespace BSFP;

use ArrayIterator;
use IteratorAggregate;
use stdClass;

use Tiimber\Exception;

class ParameterBag implements IteratorAggregate
{
  private $properties;

  private $private_properties = [];

  public function __construct($properties = null)
  {
    $this->properties = is_null($properties) 
      ? new stdClass() 
      : (object)$properties;
  }

  /**
   * Get object parameter
   *
   * @param $key String
   * @param $default mixed
   * @return mixed
   */
  public function get($key, $default = null)
  {
    return $this->properties->{$key} ?: $default;
  }

  /**
   * Set object parameter
   *
   * @param $key String
   * @param $value mixed
   * @return ParameterBag
   */
  public function set($key, $value)
  {
    $this->properties->{$key} = $value;
    return $this;
  }

  /**
   * Has object parameter
   *
   * @param $key String
   * @return boll
   */
  public function has($key)
  {
    return isset($this->properties->{$key});
  }

  /**
   * @return ArrayIterator
   */
  public function getIterator()
  {
    return new ArrayIterator($this->properties);
  }
}

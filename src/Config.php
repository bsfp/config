<?php
namespace BSFP;

use BSFP\ParameterBag;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class Config
{
  private static $instance;

  private $config
  
  private $path;

  public function __construct($path)
  {
    if (self::$instance) {
      trhow new \Exception('Config already initialized');
    }
    self::$instance = $this;
    $this->path = $path;
    $this->config = new ParameterBag();
    $this->load('json', $this->readJsonFile);
    $this->load('yml', $this->readYamlFile);
  }
  
  private function load($ext, $callback)
  {
    $this->loadConfigFiles($ext, $callback);
    $this->loadConfigFolders($ext, $callback);
  }

  /**
   * Get the config file
   *
   * @param String $key config file or folder name
   * @param mixed $default
   * @return \stdClass
   */
  public static function get($key, $default = null)
  {
    if (!self::$instance) {
      trhow new \Exception('BSFP config not initialized add "new \\BSFP\\C($path)" before using "\\BSFP\\C::get($key)"');
    }

    return self::$instance->config->get($key, $default);
  }
  
  private function readYamlFile($filename)
  {
    try {
      return Yaml::parse($filename);
    } catch (ParseException $e) {
      printf("Unable to parse the YAML string: %s", $e->getMessage());
    }
    return $content;
  }

  private function readJsonFile($filename)
  {
    $content = json_decode(file_get_contents($filename));
    if (json_last_error() !== 0) {
      throw new Exception('Bad json encoding in file: ' . $filename,  json_last_error());
    }
    return $content;
  }

  private function loadConfigFiles($ext, $callback)
  {
    $files = glob($this->path . '*.' . $ext);
    foreach($files as $file) {
      $this->config->set(basename($file, '.' . $ext), new ParameterBag($callback($file));
    }
  }

  private function loadConfigFolders($ext, $callback)
  {
    $folders = array_diff(scandir($this->path), ['..', '.']);

    foreach($folders as $folder) {
      if (!is_dir($this->path . $folder)) {
        continue;
      }
      $files = glob($this->path . $folder . '/*.' . $ext);
      $content = [];
      foreach($files as $file) {
        $content = array_merge($content, (array)$callback($file));
      }
      $this->config->set(basename($folder)], new ParameterBag($content));
    }
  }
}

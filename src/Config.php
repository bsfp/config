<?php
namespace BSFP;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class Config
{
  private static $instance;

  private $config;
  
  private $path;

  public function __construct(string $path)
  {
    if (self::$instance) {
      throw new \Exception('Config already initialized');
    }
    self::$instance = $this;
    $this->path = $path;
    $this->config = new C\Bag();
    $this->load('json', $this->readJsonFile);
    $this->load('yml', $this->readYamlFile);
    $this->load('yaml', $this->readYamlFile);
    $this->load('php', $this->readPhpFile);
  }

  /**
   * Get the config file
   *
   * @param String $key config file or folder name
   * @param mixed $default
   * @return \stdClass
   */
  public static function get(string $key, $default = null)
  {
    if (!self::$instance) {
      throw new \Exception('BSFP config not initialized add "new \\BSFP\\C($path)" before using "\\BSFP\\C::get($key)"');
    }

    return self::$instance->config->get($key, $default);
  }

  /**
   * Load files and folders content 
   */
  private function load(string $ext, callable $callback)
  {
    $this->loadConfigFiles($ext, $callback);
    $this->loadConfigFolders($ext, $callback);
  }

  /**
   * Get YAML file content
   */
  private function readYamlFile(string $filename)
  {
    try {
      return Yaml::parse($filename);
    } catch (ParseException $e) {
      printf("Unable to parse the YAML string: %s", $e->getMessage());
    }
    return $content;
  }

  /**
   * Get JSON file content
   */
  private function readJsonFile(string $filename)
  {
    $content = json_decode(file_get_contents($filename));
    if (json_last_error() !== 0) {
      throw new Exception('Bad json encoding in file: ' . $filename,  json_last_error());
    }
    return $content;
  }

  /**
   * Get PHP file content
   */
  private function readPhpFile(string $filename)
  {
    $content = include($filename);

    return $content;
  }

  /**
   * load file content
   */
  private function loadConfigFiles(string $ext, callable $callback)
  {
    $files = glob($this->path . '*.' . $ext);
    foreach ($files as $file) {
      $this->config->set(basename($file, '.' . $ext), new C\Bag($callback($file)));
    }
  }

  /**
   * load folder content
   */
  private function loadConfigFolders(string $ext, callable $callback)
  {
    $folders = array_diff(scandir($this->path), ['..', '.']);

    foreach ($folders as $folder) {
      if (!is_dir($this->path . $folder)) {
        continue;
      }
      $files = glob($this->path . $folder . '/*.' . $ext);
      $content = [];
      foreach ($files as $file) {
        $content = array_merge($content, (array)$callback($file));
      }
      $this->config->set(basename($folder), new C\Bag($content));
    }
  }
}


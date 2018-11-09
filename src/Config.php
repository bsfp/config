<?php
namespace BSFP;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class Config
{
  const ERROR_PARSING_MESSAGE = 'Unable to parse the %s file: %s, with message %s';

  private static $instance;

  private $config;
  
  private $path;

  public function __construct(string $path, ?string $ext = null)
  {
    $this->path = $path;
    $this->config = [];

    $this->load('json', '\BSFP\Config::readJsonFile');
    $this->load('yml', '\BSFP\Config::readYamlFile');
    $this->load('yaml', '\BSFP\Config::readYamlFile');
    $this->load('php', '\BSFP\Config::readPhpFile');
    
    $this->config = new C\Bag($this->config);
  }

  public static function build(string $path, ?string $ext = null): void
  {
    if (self::$instance) {
      throw new \Exception('Config already initialized');
    }
    self::$instance = new self($path, $ext);
  }

  /**
   * Get the config file
   *
   * @param String $key
   * @param mixed $default
   * @return C\Bag
   */
  public static function get(string $key, $default = null): ?C\Bag
  {
    if (!self::$instance) {
      throw new \Exception('BSFP config not initialized add "new \\BSFP\\C::build($path)" before using "\\BSFP\\C::get($key)"');
    }

    return self::$instance->config->get($key, $default);
  }

  /**
   * Get the config file
   *
   * @param String $key
   * @return C\Bag
   */
  public function __get($key): ?C\Bag
  {
    return $this->config->get($key);
  }

  /**
   * Load files and folders content 
   */
  private function load(string $ext, callable $callback): void
  {
    $this->loadConfigFiles($ext, $callback);
    $this->loadConfigFolders($ext, $callback);
  }

  /**
   * Get YAML file content
   */
  private static function readYamlFile(string $filename): array
  {
    try {
      return Yaml::parseFile($filename);
    } catch (ParseException $e) {
      throw new Exception(sprintf(self::ERROR_PARSING_MESSAGE, 'YAML', $filename, $e->getMessage()));
    }
  }

  /**
   * Get JSON file content
   */
  private static function readJsonFile(string $filename): array
  {
    $content = json_decode(file_get_contents($filename), true);
    if (json_last_error() !== 0) {
      throw new \Exception(sprintf(self::ERROR_PARSING_MESSAGE, 'JSON', $filename, json_last_error_msg()));
    }
    
    return $content;
  }

  /**
   * Get PHP file content
   */
  private static function readPhpFile(string $filename): array
  {
    try {
      $content = include($filename);
    } catch (\Throwable $e) {
      throw new \Exception(sprintf(self::ERROR_PARSING_MESSAGE, 'PHP', $filename, $e->getMessage()));
    }

    return $content;
  }

  /**
   * load file content
   */
  private function loadConfigFiles(string $ext, callable $callback): void
  {
    $files = glob($this->path . '*.' . $ext);
    foreach ($files as $file) {
      $data = call_user_func_array($callback, [$file]);
      $this->config[basename($file, '.' . $ext)] = new C\Bag($data);
    }
  }

  /**
   * load folder content
   */
  private function loadConfigFolders(string $ext, callable $callback): void
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
      $this->config[basename($folder)] = new C\Bag($content);
    }
  }
}


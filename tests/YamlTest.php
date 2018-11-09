<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class YamlTest extends TestCase
{

  public function testLoadingDataInYMLFile(): void
  {
    $cnf = new \BSFP\C(__DIR__ . '/data/yaml/');
    $this->assertEquals($cnf->hello->what, 'world');
  }
}


<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{

  public function testLoadingDataInYMLFile(): void
  {
    $cnf = new \BSFP\C(__DIR__ . '/data/json/');
    $this->assertEquals($cnf->hello->what, 'world');
  }
}


<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class YmlTest extends TestCase
{

  public function testLoadingDataInYMLFile(): void
  {
    $cnf = new \BSFP\C(__DIR__ . '/data/yml/');
    $this->assertEquals($cnf->hello->what, 'world');
  }
}


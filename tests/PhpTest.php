<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class PhpTest extends TestCase
{

  public function testLoadingDataInPHPFile(): void
  {
    $cnf = new \BSFP\C(__DIR__ . '/data/php/');
    $this->assertEquals($cnf->hello->what, 'world');
  }
}


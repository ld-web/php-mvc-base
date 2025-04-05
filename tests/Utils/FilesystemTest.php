<?php

namespace Tests\Utils;

use App\Utils\Filesystem;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    public function testGetFqcns()
    {
        $fqcns = Filesystem::getFqcns(__DIR__, "Tests\\Utils\\");
        $this->assertEquals(['Tests\Utils\FilesystemTest'], $fqcns);
    }
}

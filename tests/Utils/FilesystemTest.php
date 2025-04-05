<?php

namespace Tests\Utils;

use App\Utils\Filesystem;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    public function testGetFqcns()
    {
        $fqcns = Filesystem::getFqcns(__DIR__, "App\\Tests\\Utils\\");
        $this->assertEquals(['App\Tests\Utils\FilesystemTest'], $fqcns);
    }
}

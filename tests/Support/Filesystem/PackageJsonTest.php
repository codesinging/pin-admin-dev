<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support\Filesystem;

use CodeSinging\PinAdmin\Support\Filesystem\PackageJson;
use Tests\TestCase;

class PackageJsonTest extends TestCase
{
    public function testFilename()
    {
        $packageJson = new PackageJson();

        self::assertEquals(base_path('package.json'), $packageJson->filename());
    }
}

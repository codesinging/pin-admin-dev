<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Foundation;

use CodeSinging\PinAdmin\Foundation\Admin;
use Tests\TestCase;

class AdminTest extends TestCase
{
    public function testName()
    {
        Admin::boot('admin');
        self::assertEquals('admin', Admin::name());
        self::assertEquals('Admin', Admin::studlyName());
    }
}

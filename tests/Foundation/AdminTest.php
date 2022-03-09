<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Foundation;

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Foundation\Application;
use Tests\TestCase;

class AdminTest extends TestCase
{
    public function testApp()
    {
        self::assertInstanceOf(Application::class, Admin::app());
        self::assertSame(Admin::app(), Admin::app());
    }

    public function testName()
    {
        Admin::boot('admin');
        self::assertEquals('admin', Admin::name());
        self::assertEquals('Admin', Admin::studlyName());
    }
}

<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Foundation;

use CodeSinging\PinAdmin\Foundation\Application;
use CodeSinging\PinAdmin\Foundation\Manager;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testRegister()
    {
        self::assertInstanceOf(Application::class, $this->app[Manager::label()]);
        self::assertSame(app(Manager::LABEL), app(Manager::LABEL));
    }
}

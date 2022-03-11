<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Console;

use CodeSinging\PinAdmin\Console\AdminCommand;
use Tests\TestCase;

class AdminCommandTest extends TestCase
{
    public function testCommand()
    {
        $this->artisan('admin')->assertSuccessful();
    }
}

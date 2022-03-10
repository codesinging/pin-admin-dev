<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Console;

use CodeSinging\PinAdmin\Console\ListCommand;
use Tests\TestCase;

class ListCommandTest extends TestCase
{
    public function testCommand()
    {
        $this->artisan('admin:list')->assertSuccessful();
    }
}

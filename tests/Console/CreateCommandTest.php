<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Console;

use CodeSinging\PinAdmin\Exception\AdminException;
use CodeSinging\PinAdmin\Foundation\Manager;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Manager::clear();
    }

    /**
     * @throws AdminException
     */
    public function testCommand()
    {
        $this->artisan('admin:create admin')
            ->assertSuccessful();


        self::assertTrue(Manager::exists('admin'));
    }
}

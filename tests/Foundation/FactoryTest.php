<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Foundation;

use CodeSinging\PinAdmin\Exception\AdminException;
use CodeSinging\PinAdmin\Foundation\Application;
use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Foundation\Manager;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    protected Application $application;
    protected Factory $factory;

    /**
     * @throws AdminException
     */
    protected function create(): void
    {
        $this->factory = new Factory('admin');
        $this->factory->create();
        $this->application = new Application('admin');
    }

    protected function tearDown(): void
    {
        Manager::clear();
    }

    /**
     * @throws AdminException
     */
    public function testReplace()
    {
        $this->create();
        self::assertEquals('admin', $this->factory->replace('__DUMMY_NAME__'));
        self::assertEquals('admin_admin', $this->factory->replace('__DUMMY_LABEL_____DUMMY_NAME__'));
    }

    /**
     * @throws AdminException
     */
    public function testStubPath()
    {
        $this->create();
        self::assertEquals(Manager::packagePath('stubs'), $this->factory->stubPath());
        self::assertEquals(Manager::packagePath('stubs/routes/web.php'), $this->factory->stubPath('routes/web.php'));
    }

    /**
     * @throws AdminException
     */
    public function testCreateRootDirectories()
    {
        $this->create();

        self::assertDirectoryExists(Manager::rootPath());
        self::assertDirectoryExists(Manager::rootAppPath());
        self::assertDirectoryExists(Manager::rootPublicPath());
    }

    /**
     * @throws AdminException
     */
    public function testCreateDirectories()
    {
        $this->create();

        self::assertDirectoryExists($this->application->path());
        self::assertDirectoryExists($this->application->appPath());
        self::assertDirectoryExists($this->application->publicPath());
    }

    /**
     * @throws AdminException
     */
    public function testCreateRoutes()
    {
        $this->create();

        self::assertFileExists($this->application->path('routes/web.php'));
    }

    /**
     * @throws AdminException
     */
    public function testCreateResources()
    {
        $this->create();

        self::assertDirectoryExists($this->application->path('resources'));
    }

    /**
     * @throws AdminException
     */
    public function testCreateConfig()
    {
        $this->create();
        self::assertFileExists($this->application->path('config/app.php'));
        self::assertEquals('Admin', $this->application->config('name'));
    }

    /**
     * @throws AdminException
     */
    public function testCreateModels()
    {
        $this->create();

        self::assertFileExists($this->application->appPath('Models/AdminUser.php'));
    }

    /**
     * @throws AdminException
     */
    public function testCreateControllers()
    {
        $this->create();

        self::assertFileExists($this->application->appPath('Controllers/IndexController.php'));
    }
}

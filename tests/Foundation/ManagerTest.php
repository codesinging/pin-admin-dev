<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Foundation;

use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Foundation\Manager;
use Tests\TestCase;

class ManagerTest extends TestCase
{
    public function testVersion()
    {
        self::assertEquals(Manager::VERSION, Manager::version());
    }

    public function testBrand()
    {
        self::assertEquals(Manager::BRAND, Manager::brand());
    }

    public function testLabel()
    {
        self::assertEquals('admin', Manager::label());
        self::assertEquals('admin_config', Manager::label('config'));
        self::assertEquals('admin-config', Manager::label('config', '-'));
    }

    public function testRootDirectory()
    {
        self::assertEquals('admins', Manager::rootDirectory());
        self::assertEquals('admins' . DIRECTORY_SEPARATOR . 'admin', Manager::rootDirectory('admin'));
        self::assertEquals('admins' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'config', Manager::rootDirectory('admin', 'config'));
    }

    public function testRootPath()
    {
        self::assertEquals(base_path('admins'), Manager::rootPath());
        self::assertEquals(base_path('admins' . DIRECTORY_SEPARATOR . 'admin'), Manager::rootPath('admin'));
        self::assertEquals(base_path('admins' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'config'), Manager::rootPath('admin', 'config'));
    }

    public function testRootAppDirectory()
    {
        self::assertEquals('Admins', Manager::rootAppDirectory());
        self::assertEquals('Admins' . DIRECTORY_SEPARATOR . 'Admin', Manager::rootAppDirectory('Admin'));
        self::assertEquals('Admins' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'Controllers', Manager::rootAppDirectory('Admin', 'Controllers'));
    }

    public function testRootAppPath()
    {
        self::assertEquals(app_path('Admins'), Manager::rootAppPath());
        self::assertEquals(app_path('Admins' . DIRECTORY_SEPARATOR . 'Admin'), Manager::rootAppPath('Admin'));
        self::assertEquals(app_path('Admins' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'Controllers'), Manager::rootAppPath('Admin', 'Controllers'));
    }

    public function testRootPublicDirectory()
    {
        self::assertEquals('admins', Manager::rootPublicDirectory());
        self::assertEquals('admins' . DIRECTORY_SEPARATOR . 'admin', Manager::rootPublicDirectory('admin'));
        self::assertEquals('admins' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'js', Manager::rootPublicDirectory('admin', 'js'));
    }

    public function testRootPublicPath()
    {
        self::assertEquals(public_path('admins'), Manager::rootPublicPath());
        self::assertEquals(public_path('admins' . DIRECTORY_SEPARATOR . 'admin'), Manager::rootPublicPath('admin'));
        self::assertEquals(public_path('admins' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'js'), Manager::rootPublicPath('admin', 'js'));
    }

    public function testPackagePath()
    {
        self::assertEquals(dirname(__DIR__), Manager::packagePath('tests'));
        self::assertEquals(__DIR__, Manager::packagePath('tests', 'Foundation'));
    }

    public function testLoadApplications()
    {
        Manager::clear();

        self::assertCount(0, Manager::applications());

        $factory = new Factory('admin');
        $factory->create();

        self::assertCount(1, Manager::applications());

        Manager::clear();
    }

    public function testApplications()
    {
        Manager::clear();
        $factory = new Factory('admin');
        $factory->create();

        self::assertIsArray(Manager::applications());
        self::assertCount(1, Manager::applications());
        self::assertEquals('admin', Manager::applications()['admin']->name());

        $factory->destroy();
        Manager::clear();
    }

    public function testExists()
    {
        $factory = new Factory('admin');
        $factory->destroy();

        self::assertFalse(Manager::exists('admin'));

        $factory->create();

        self::assertTrue(Manager::exists('admin'));

        $factory->destroy();
    }

    public function testHas()
    {
        $factory = new Factory('admin');
        $factory->destroy();

        self::assertFalse(Manager::has('admin'));

        $factory->create();

        self::assertTrue(Manager::has('admin'));

        $factory->destroy();
    }

    public function testDestroy()
    {
        $factory = new Factory('admin');
        $factory->create();

        self::assertTrue(Manager::exists('admin'));
        self::assertTrue(Manager::has('admin'));

        Manager::destroy('admin');

        self::assertFalse(Manager::exists('admin'));
        self::assertFalse(Manager::has('admin'));
    }

    public function testClear()
    {
        $factory = new Factory('admin');
        $factory->create();

        self::assertTrue(Manager::exists('admin'));
        self::assertTrue(Manager::has('admin'));

        Manager::clear();

        self::assertFalse(Manager::exists('admin'));
        self::assertFalse(Manager::has('admin'));
    }
}

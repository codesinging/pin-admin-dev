<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support;

use CodeSinging\PinAdmin\Foundation\Application;
use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Foundation\Manager;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    public function testAdmin()
    {
        self::assertInstanceOf(Application::class, admin());
        self::assertSame(admin(), admin());
    }

    public function testAdminConfig()
    {
        admin('admin')->config(['title' => 'Title']);

        self::assertInstanceOf(Repository::class, admin_config());
        self::assertIsArray(admin_config()->all());
        self::assertEquals('Title', admin_config('title'));
        self::assertNull(admin_config('key_not_exists'));
        self::assertEquals('Default', admin_config('key_not_exists', 'Default'));
    }

    public function testAdminUrl()
    {
        admin('admin');
        self::assertEquals(url('admin'), admin_url());
        self::assertEquals(url('admin/home'), admin_url('home'));
    }

    public function testAdminLink()
    {
        admin('admin');
        self::assertEquals('/admin', admin_link());
        self::assertEquals('/admin/home', admin_link('home'));
        self::assertEquals('/admin/home?id=1', admin_link('home', ['id' => 1]));
    }

    public function testAdminAsset()
    {
        admin('admin');
        self::assertEquals('/static/app.js', admin_asset('/static/app.js'));
        self::assertEquals('/admins/admin', admin_asset());
        self::assertEquals('/admins/admin/js/app.js', admin_asset('js/app.js'));
    }

    /**
     * @throws Exception
     */
    public function testAdminMix()
    {
        admin('admin');

        File::ensureDirectoryExists(admin()->publicPath(), 0755, true);
        File::put(admin()->publicPath('mix-manifest.json'), '{"/js/app.js":"/js/app.js"}');

        self::assertEquals('/admins/admin/js/app.js', admin_mix('js/app.js'));

        File::deleteDirectory(Manager::rootPublicPath());
    }

    public function testAdminTemplate()
    {
        admin('admin');
        self::assertEquals('admin_admin::layout.app', admin_template('layout.app'));
    }

    public function testCreateViewApplication(): Factory
    {
        Manager::clear();
        $factory = new Factory('admin');
        $factory->create();
        self::assertTrue(Manager::exists('admin'));
        return $factory;
    }

    /**
     * @depends testCreateViewApplication
     * @return void
     */
    public function testAdminView(Factory $factory)
    {
        admin('admin');
        self::assertEquals(view('admin_admin::public.page'), admin_view('public.page'));
        $factory->destroy();
        Manager::clear();
    }

    public function testCreatePageApplication(): Factory
    {
        Manager::clear();
        $factory = new Factory('admin');
        $factory->create();
        self::assertTrue(Manager::exists('admin'));
        return $factory;
    }

    /**
     * @depends testCreatePageApplication
     *
     * @param Factory $factory
     *
     * @return void
     */
    public function testAdminPage(Factory $factory)
    {
        admin('admin');
        self::assertEquals(view('admin_admin::public.page', ['path' => 'index']), admin_page('index'));
        $factory->destroy();
        Manager::clear();
    }

    public function testCreateAuthApplication(): Factory
    {
        Manager::clear();
        $factory = new Factory('admin');
        $factory->create();
        self::assertTrue(Manager::exists('admin'));
        return $factory;
    }

    /**
     * @depends testCreateAuthApplication
     *
     * @return void
     */
    public function testAdminAuth()
    {
        admin('admin');

        self::assertEquals(Auth::guard(admin()->guard()), admin_auth());

        Manager::clear();
    }

    public function testCreateUserApplication(): Factory
    {
        Manager::clear();
        $factory = new Factory('admin');
        $factory->create();
        self::assertTrue(Manager::exists('admin'));
        return $factory;
    }

    /**
     * @depends testCreateUserApplication
     * @return void
     */
    public function testUser()
    {
        admin('admin');

        self::assertEquals(Auth::guard(admin()->guard())->user(), admin_user());

        Manager::clear();
    }
}

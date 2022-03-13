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
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    public function testApp()
    {
        self::assertInstanceOf(Application::class, (new Application())->app());
        self::assertSame(app(Manager::LABEL), (new Application())->app());
        self::assertSame((new Application())->app(), (new Application())->app());
        self::assertEquals('admin', (new Application())->app('admin')->name());
    }

    public function testName()
    {
        self::assertEquals('admin', (new Application('admin'))->name());
        self::assertEquals('admin_users', (new Application('admin'))->name('users'));
        self::assertEquals('admin-config', (new Application('admin'))->name('config', '-'));
    }

    public function testStudlyName()
    {
        self::assertEquals('Admin', (new Application())->boot('admin')->studlyName());
        self::assertEquals('AdminUser', (new Application())->boot('admin')->studlyName('user'));
    }

    public function testGuard()
    {
        self::assertEquals('admin', (new Application('admin'))->guard());
    }

    public function testDirectory()
    {
        self::assertEquals('admins/user', (new Application('user'))->directory());
        self::assertEquals('admins/user/config', (new Application('user'))->directory('config'));
        self::assertEquals('admins/user/config/app.php', (new Application('user'))->directory('config', 'app.php'));
    }

    public function testPath()
    {
        self::assertEquals(base_path('admins/user'), (new Application('user'))->path());
        self::assertEquals(base_path('admins/user/config'), (new Application('user'))->path('config'));
        self::assertEquals(base_path('admins/user/config/app.php'), (new Application('user'))->path('config', 'app.php'));
    }

    public function testAppDirectory()
    {
        self::assertEquals('Admins/Admin', (new Application('admin'))->appDirectory());
        self::assertEquals('Admins/Admin/Controllers', (new Application('admin'))->appDirectory('Controllers'));
        self::assertEquals('Admins/Admin/Controllers/Controller.php', (new Application('admin'))->appDirectory('Controllers', 'Controller.php'));
    }

    public function testAppPath()
    {
        self::assertEquals(app_path('Admins/Admin'), (new Application('admin'))->appPath());
        self::assertEquals(app_path('Admins/Admin/Controllers'), (new Application('admin'))->appPath('Controllers'));
        self::assertEquals(app_path('Admins/Admin/Controllers/Controller.php'), (new Application('admin'))->appPath('Controllers', 'Controller.php'));
    }

    public function testPublicDirectory()
    {
        self::assertEquals('admins/admin', (new Application('admin'))->publicDirectory());
        self::assertEquals('admins/admin/js', (new Application('admin'))->publicDirectory('js'));
        self::assertEquals('admins/admin/js/app.js', (new Application('admin'))->publicDirectory('js', 'app.js'));
    }

    public function testPublicPath()
    {
        self::assertEquals(public_path('admins/admin'), (new Application('admin'))->publicPath());
        self::assertEquals(public_path('admins/admin/js'), (new Application('admin'))->publicPath('js'));
        self::assertEquals(public_path('admins/admin/js/app.js'), (new Application('admin'))->publicPath('js', 'app.js'));
    }

    public function testGetNamespace()
    {
        self::assertEquals('App\\Admins\\Admin', (new Application('admin'))->getNamespace());
        self::assertEquals('App\\Admins\\Admin\\Controllers', (new Application('admin'))->getNamespace('Controllers'));
    }

    public function testVerify()
    {
        self::assertTrue((new Application())->verify('admin'));
        self::assertTrue((new Application())->verify('admin123'));
        self::assertTrue((new Application())->verify('admin_123'));
        self::assertTrue((new Application())->verify('admin_shop'));
        self::assertFalse((new Application())->verify('Admin'));
        self::assertFalse((new Application())->verify('123'));
        self::assertFalse((new Application())->verify('admin.123'));
        self::assertFalse((new Application())->verify('admin-123'));
        self::assertFalse((new Application())->verify('admin-user'));
    }

    public function testBoot()
    {
        self::assertInstanceOf(Application::class, (new Application())->boot('admin'));
        self::assertEquals('admin', (new Application())->boot('admin')->name());
        self::assertEquals('shop', (new Application())->boot('shop')->name());
    }

    public function testConfig()
    {
        $app = new Application('admin');

        $app->config(['title' => 'Title']);

        self::assertInstanceOf(Repository::class, $app->config());
        self::assertIsArray($app->config()->all());
        self::assertEquals('Title', $app->config('title'));
        self::assertNull($app->config('key_not_exists'));
        self::assertEquals('Default', $app->config('key_not_exists', 'Default'));
    }

    public function testRoutePrefix()
    {
        $app = new Application('admin');

        self::assertEquals('admin', $app->routePrefix());

        $app->config(['route_prefix' => 'admin123']);
        self::assertEquals('admin123', $app->routePrefix());
    }

    public function testUrl()
    {
        self::assertEquals(url('admin'), (new Application('admin'))->url());
        self::assertEquals(url('admin/home'), (new Application('admin'))->url('home'));
    }

    public function testLink()
    {
        self::assertEquals('/admin', (new Application('admin'))->link());
        self::assertEquals('/admin/home', (new Application('admin'))->link('home'));
        self::assertEquals('/admin/home?id=1', (new Application('admin'))->link('home', ['id' => 1]));
    }

    public function testAsset()
    {
        self::assertEquals('/static/app.js', (new Application())->asset('/static/app.js'));
        self::assertEquals('/admins/admin', (new Application('admin'))->asset());
        self::assertEquals('/admins/admin/js/app.js', (new Application('admin'))->asset('js/app.js'));
    }

    /**
     * @throws Exception
     */
    public function testMix()
    {
        $application = new Application('admin');

        File::ensureDirectoryExists($application->publicPath(), 0755, true);
        File::put($application->publicPath('mix-manifest.json'), '{"/js/app.js":"/js/app.js"}');

        self::assertEquals('/admins/admin/js/app.js', $application->mix('js/app.js'));

        File::deleteDirectory(Manager::rootPublicPath());
    }

    public function testTemplate()
    {
        self::assertEquals('admin_admin::layout.app', (new Application('admin'))->template('layout.app'));
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
    public function testView(Factory $factory)
    {
        self::assertEquals(view('admin_admin::public.page'), (new Application('admin'))->view('public.page'));
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
    public function testPage(Factory $factory)
    {
        self::assertEquals(view('admin_admin::public.page', ['path' => 'index']), (new Application('admin'))->page('index'));
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
    public function testAuth()
    {
        $application = new Application('admin');

        self::assertEquals(Auth::guard($application->guard()), $application->auth());

        Manager::clear();
    }

    /**
     * @throws AdminException
     */
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
        $application = new Application('admin');

        self::assertEquals(Auth::guard($application->guard())->user(), $application->user());

        Manager::clear();
    }

    public function testRouteGroup()
    {
        
    }
}

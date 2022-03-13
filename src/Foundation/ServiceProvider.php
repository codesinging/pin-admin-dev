<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Foundation;

use CodeSinging\PinAdmin\Console\AdminCommand;
use CodeSinging\PinAdmin\Console\CreateCommand;
use CodeSinging\PinAdmin\Console\ListCommand;
use CodeSinging\PinAdmin\Middleware\Auth;
use CodeSinging\PinAdmin\Middleware\Guest;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

/**
 * PinAdmin 服务提供者
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var array
     */
    protected array $commands = [
        AdminCommand::class,
        CreateCommand::class,
        ListCommand::class,
    ];

    /**
     * @var array
     */
    protected array $middlewares = [
    ];

    /**
     * @return void
     */
    public function register()
    {
        $this->registerBinding();
    }

    /**
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()){
            $this->registerCommands();
            $this->registerMigrations();
        }

        if (!$this->app->routesAreCached()){
            $this->registerRoutes();
        }

        $this->registerMiddlewares();
        $this->registerViews();
        $this->configureAuthentication();
    }

    /**
     * @return void
     */
    private function registerBinding()
    {
        $this->app->singleton(Manager::label(), Application::class);
    }

    /**
     * @return void
     */
    private function registerCommands()
    {
        $this->commands($this->commands);
    }

    /**
     * @return void
     */
    private function registerMigrations()
    {
        foreach (Manager::applications() as $application) {
            $this->loadMigrationsFrom($application->path('migrations'));
        }
    }

    /**
     * @return void
     */
    private function registerRoutes()
    {
        foreach (Manager::applications() as $application) {
            $this->loadRoutesFrom($application->path('routes/web.php'));
        }
    }

    /**
     * @return void
     */
    private function registerMiddlewares()
    {
        /** @var Router $router */
        $router = $this->app['router'];

        $this->middlewares[Manager::label('auth','.')] = Auth::class;
        $this->middlewares[Manager::label('guest','.')] = Guest::class;

        foreach ($this->middlewares as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
    }

    /**
     * @return void
     */
    private function registerViews()
    {
        $this->loadViewsFrom(Manager::packagePath('resources/views'), Manager::label());
        foreach (Manager::applications() as $application) {
            $namespace = Manager::label($application->name(), '_');
            $this->loadViewsFrom($application->path('resources/views'), $namespace);
        }
    }

    /**
     * @return void
     */
    private function configureAuthentication()
    {
        foreach (Manager::applications() as $application) {
            Config::set('auth.guards.' . $application->guard(), $application->config('auth_guard'));
            Config::set('auth.providers.' . $application->config('auth_guard.provider'), $application->config('auth_provider'));
        }
    }
}

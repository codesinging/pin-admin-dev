<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Foundation;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\Factory;
use Illuminate\View\View;
use JetBrains\PhpStorm\Pure;

/**
 * 应用程序门面
 *
 * @method static string name(string $suffix = null, string $separator = '_')
 * @method static string studlyName(string $suffix = '')
 * @method static string guard()
 * @method static string directory(?string ...$paths)
 * @method static string path(?string ...$paths)
 * @method static string appDirectory(?string ...$paths)
 * @method static string appPath(?string ...$paths)
 * @method static string publicDirectory(?string ...$paths)
 * @method static string publicPath(?string ...$paths)
 * @method static string getNamespace(?string ...$paths)
 * @method static Application boot(string $name)
 * @method static Application|array|Repository|mixed config(array|string $key = null, mixed $default = null)
 * @method static string routePrefix()
 * @method static string url(string $path = null, array $parameters = [], bool $secure = null)
 * @method static string link(string $path = null, array $parameters = [])
 * @method static string asset(?string ...$paths)
 * @method static string mix(string $path)
 * @method static string template(string $path)
 * @method static Factory|View view(string $view = null, array $data = [], array $mergeData = [])
 * @method static Factory|View page(string $path)
 * @method static Guard|StatefulGuard auth()
 * @method static Authenticatable|null user()
 */
class Admin extends Facade
{
    #[Pure]
    protected static function getFacadeAccessor(): string
    {
        return Manager::label();
    }
}

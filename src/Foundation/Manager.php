<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Foundation;

use CodeSinging\PinAdmin\Exception\AdminException;
use Illuminate\Support\Facades\File;

/**
 * PinAdmin 应用管理器类
 */
class Manager
{
    /**
     * PinAdmin 版本号
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * PinAdmin 品牌名称
     */
    const BRAND = 'PinAdmin';

    /**
     * PinAdmin 标记
     */
    const LABEL = 'admin';

    /**
     * PinAdmin 应用根目录
     */
    const ROOT_DIRECTORY = 'admins';

    /**
     * PinAdmin 应用类文件根目录
     */
    const ROOT_APP_DIRECTORY = 'Admins';

    /**
     * PinAdmin 应用公共文件根目录
     */
    const ROOT_PUBLIC_DIRECTORY = 'admins';

    /**
     * @var Application[]
     */
    protected static array $applications;

    /**
     * 返回 PinAdmin 版本号
     *
     * @return string
     */
    public static function version(): string
    {
        return self::VERSION;
    }

    /**
     * 返回 PinAdmin 的品牌名
     *
     * @return string
     */
    public static function brand(): string
    {
        return self::BRAND;
    }

    /**
     * 返回 PinAdmin 标记及以该标记为前缀的字符串
     *
     * @param string|null $suffix
     * @param string $separator
     *
     * @return string
     */
    public static function label(string $suffix = null, string $separator = '_'): string
    {
        return self::LABEL . ($suffix ? $separator . $suffix : '');
    }

    /**
     * 返回 PinAdmin 应用根目录或指定子目录
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public static function rootDirectory(?string ...$paths): string
    {
        array_unshift($paths, self::ROOT_DIRECTORY);
        return implode(DIRECTORY_SEPARATOR, $paths);
    }

    /**
     * 返回 PinAdmin 应用根路径或指定子路径
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public static function rootPath(?string ...$paths): string
    {
        return base_path(self::rootDirectory(...$paths));
    }

    /**
     * 返回 PinAdmin 应用类文件根目录
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public static function rootAppDirectory(?string ...$paths): string
    {
        array_unshift($paths, self::ROOT_APP_DIRECTORY);
        return implode(DIRECTORY_SEPARATOR, $paths);
    }

    /**
     * 返回 PinAdmin 应用类文件根路径或指定子路径
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public static function rootAppPath(?string ...$paths): string
    {
        return app_path(self::rootAppDirectory(...$paths));
    }

    /**
     * 返回 PinAdmin 应用公共文件根目录
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public static function rootPublicDirectory(?string ...$paths): string
    {
        array_unshift($paths, self::ROOT_PUBLIC_DIRECTORY);
        return implode(DIRECTORY_SEPARATOR, $paths);
    }

    /**
     * 返回 PinAdmin 应用公共文件根路径或指定子路径
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public static function rootPublicPath(?string ...$paths): string
    {
        return public_path(self::rootPublicDirectory(...$paths));
    }

    /**
     * 返回 PinAdmin 包路径
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public static function packagePath(?string ...$paths): string
    {
        array_unshift($paths, dirname(__DIR__, 2));
        return implode(DIRECTORY_SEPARATOR, $paths);
    }

    /**
     * 加载所有应用
     *
     * @return void
     * @throws AdminException
     */
    public static function loadApplications()
    {
        self::$applications = [];

        if (File::isDirectory(self::rootPath())) {
            $directories = File::directories(self::rootPath());
            foreach ($directories as $directory) {
                $application = new Application(basename($directory));
                self::$applications[$application->name()] = $application;
            }
        }
    }

    /**
     * 返回所有应用
     *
     * @return Application[]
     */
    public static function applications(): array
    {
        if (!isset(self::$applications)) {
            self::loadApplications();
        }
        return self::$applications ?? [];
    }

    /**
     * 应用目录是否存在
     *
     * @param string|Application $application
     *
     * @return bool
     * @throws AdminException
     */
    public static function exists(string|Application $application): bool
    {
        is_string($application) and $application = new Application($application);
        return file_exists($application->path());
    }

    /**
     * 应用是否已经存在
     *
     * @param string|Application $application
     *
     * @return bool
     * @throws AdminException
     */
    public static function has(string|Application $application): bool
    {
        is_string($application) and $application = new Application($application);
        return array_key_exists($application->name(), self::applications());
    }

    /**
     * 删除应用
     *
     * @param string|Application $application
     *
     * @return void
     */
    public static function destroy(string|Application $application)
    {
        is_string($application) and $application = self::$applications[$application];

        File::deleteDirectory($application->path());
        File::deleteDirectory($application->appPath());
        File::deleteDirectory($application->publicPath());
        unset(self::$applications[$application->name()]);
    }

    /**
     * 清空所有应用
     *
     * @return void
     */
    public static function clear()
    {
        self::$applications = [];
        File::deleteDirectory(self::rootPath());
        File::deleteDirectory(self::rootAppPath());
        File::deleteDirectory(self::rootPublicPath());
    }
}

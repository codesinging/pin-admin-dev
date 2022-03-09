<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Foundation\Application;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\View\Factory;
use Illuminate\View\View;

if (!function_exists('admin')) {
    /**
     * 返回 PinAdmin 应用单例实例
     *
     * @param string|null $name
     *
     * @return Application
     */
    function admin(string $name = null): Application
    {
        return Admin::app($name);
    }
}

if (!function_exists('admin_config')) {
    /**
     * 设置或获取应用配置，或者返回应用实例
     *
     * @param array|string|null $key
     * @param mixed|null $default
     *
     * @return array|Application|Repository|mixed
     */
    function admin_config(array|string $key = null, mixed $default = null)
    {
        return Admin::app()->config($key, $default);
    }
}

if (!function_exists('admin_url')) {
    /**
     * 返回 PinAdmin 应用的链接地址
     *
     * @param string|null $path
     * @param array $parameters
     * @param bool|null $secure
     *
     * @return string
     */
    function admin_url(string $path = null, array $parameters = [], bool $secure = null): string
    {
        return Admin::app()->url($path, $parameters, $secure);
    }
}

if (!function_exists('admin_link')) {
    /**
     * 获取 PinAdmin 应用的绝对链接地址
     *
     * @param string|null $path
     * @param array $parameters
     *
     * @return string
     */
    function admin_link(string $path = null, array $parameters = []): string
    {
        return Admin::app()->link($path, $parameters);
    }
}

if (!function_exists('admin_asset')) {
    /**
     * 返回当前应用的静态文件地址
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    function admin_asset(?string ...$paths): string
    {
        return Admin::app()->asset(...$paths);
    }
}

if (!function_exists('admin_mix')) {
    /**
     * 返回带版本号的静态资源文件路径
     *
     * @param string $path
     *
     * @return string
     * @throws Exception
     */
    function admin_mix(string $path): string
    {
        return Admin::app()->mix($path);
    }
}

if (!function_exists('admin_template')) {
    /**
     * 返回位于 PinAdmin 应用目录的模板名
     *
     * @param string $path
     *
     * @return string
     * @throws Exception
     */
    function admin_template(string $path): string
    {
        return Admin::app()->template($path);
    }
}

if (!function_exists('admin_view')) {
    /**
     * 返回位于 PinAdmin 应用目录的视图内容
     *
     * @param string|null $view
     * @param array $data
     * @param array $mergeData
     *
     * @return Factory|View
     */
    function admin_view(string $view = null, array $data = [], array $mergeData = []): Factory|View
    {
        return Admin::app()->view($view, $data, $mergeData);
    }
}

if (!function_exists('admin_page')) {
    /**
     * 返回位于 PinAdmin 应用目录内的单文件组件内容
     *
     * @param string $path
     *
     * @return Factory|View
     */
    function admin_page(string $path): Factory|View
    {
        return Admin::app()->page($path);
    }
}

if (!function_exists('admin_auth')) {
    /**
     * 返回 PinAdmin 认证实例
     *
     * @return Guard|StatefulGuard
     */
    function admin_auth(): Guard|StatefulGuard
    {
        return Admin::app()->auth();
    }
}

if (!function_exists('admin_user')) {
    /**
     * 返回 PinAdmin 认证用户
     *
     * @return Authenticatable|null
     */
    function admin_user(): ?Authenticatable
    {
        return Admin::app()->user();
    }
}

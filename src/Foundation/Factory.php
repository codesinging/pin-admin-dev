<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Foundation;

use CodeSinging\PinAdmin\Exception\AdminException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * 应用工厂类
 */
class Factory
{
    /**
     * @var Application
     */
    protected Application $application;

    /**
     * @param string|Application $application
     *
     * @throws AdminException
     */
    public function __construct(string|Application $application)
    {
        $this->application = is_string($application) ? new Application($application) : $application;
    }

    /**
     * 创建应用
     *
     * @return void
     * @throws AdminException
     */
    public function create()
    {
        $this->createRootDirectories();
        $this->createDirectories();
        $this->createRoutes();
        $this->createResources();
        $this->createConfig();

        Manager::loadApplications();
    }

    /**
     * 销毁应用
     *
     * @return void
     */
    public function destroy()
    {
        Manager::destroy($this->application);
    }

    /**
     * 需要替换的标记和内容
     *
     * @return array
     */
    private function replaces(): array
    {
        return [
            '__DUMMY_LABEL__' => Manager::label(),
            '__DUMMY_UPPER_LABEL__' => Str::upper(Manager::label()),
            '__DUMMY_NAME__' => $this->application->name(),
            '__DUMMY_STUDLY_NAME__' => $this->application->studlyName(),
            '__DUMMY_CAMEL_NAME__' => Str::camel($this->application->name()),
            '__DUMMY_UPPER_NAME__' => Str::upper($this->application->name()),
            '__DUMMY_GUARD__' => $this->application->guard(),
            '__DUMMY_NAMESPACE__' => $this->application->getNamespace(),
            '__DUMMY_DIST_PATH__' => 'public/' . $this->application->publicDirectory(),
            '__DUMMY_SRC_PATH__' => $this->application->directory('resources'),
            '__DUMMY_DIRECTORY__' => $this->application->directory(),
            '__DUMMY_BASE_URL__' => $this->application->link(),
            '__DUMMY_HOME_URL__' => $this->application->link(),
            '__DUMMY_FULL_HOME_URL__' => $this->application->url(),
            '__DUMMY_ASSET_URL__' => $this->application->asset(),
        ];
    }

    /**
     * 替换标记
     *
     * @param string $content
     *
     * @return array|string
     */
    public function replace(string $content): array|string
    {
        foreach ($this->replaces() as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        return $content;
    }

    /**
     * 获取应用存根文件名
     *
     * @param string ...$paths
     *
     * @return string
     */
    public function stubPath(string ...$paths): string
    {
        $filename = Manager::packagePath('stubs', ...$paths);
        return $this->replace($filename);
    }

    /**
     * 创建根目录
     *
     * @return void
     */
    private function createRootDirectories()
    {
        File::ensureDirectoryExists(Manager::rootPath());
        File::ensureDirectoryExists(Manager::rootAppPath());
        File::ensureDirectoryExists(Manager::rootPublicPath());
    }

    /**
     * 创建基础目录
     *
     * @return void
     */
    private function createDirectories()
    {
        File::makeDirectory($this->application->path());
        File::makeDirectory($this->application->appPath());
        File::makeDirectory($this->application->publicPath());
    }

    /**
     * 创建路由文件
     *
     * @return void
     */
    private function createRoutes()
    {
        $this->createFiles($this->stubPath('routes'), $this->application->path('routes'));
    }

    /**
     * 创建资源文件
     *
     * @return void
     */
    private function createResources()
    {
        File::copyDirectory(Manager::packagePath('resources'), $this->application->path('resources'));
    }

    /**
     * 创建配置文件
     *
     * @return void
     */
    private function createConfig()
    {
        $this->createFiles($this->stubPath('config'), $this->application->path('config'));
    }

    /**
     * 根据存根创建文件
     *
     * @param string $stub
     * @param string $dest
     *
     * @return void
     */
    private function createFile(string $stub, string $dest)
    {
        if (File::isFile($stub)) {
            $content = File::get($stub);
            $content = $this->replace($content);

            File::ensureDirectoryExists(dirname($dest));

            File::put($dest, $content);
        }
    }

    /**
     * 从存根目录中创建文件
     *
     * @param string $stubDirectory
     * @param string $destDirectory
     *
     * @return void
     */
    private function createFiles(string $stubDirectory, string $destDirectory)
    {
        if (File::isDirectory($stubDirectory)) {
            $files = File::files($stubDirectory);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $filename = $this->replace($filename);

                $this->createFile($file->getPathname(), $destDirectory . DIRECTORY_SEPARATOR . $filename);
            }
        }
    }
}

<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Support\Filesystem;

class PackageJson extends JsonFile
{
    /**
     * @param string|null $filename
     */
    public function __construct(string $filename = null)
    {
        $filename = $filename ?: base_path('package.json');
        parent::__construct($filename);
    }

    /**
     * @param array $scripts
     *
     * @return bool|int
     */
    public function addScripts(array $scripts): bool|int
    {
        return $this->merge('scripts', $scripts)->write();
    }

    /**
     * @param array $devDependencies
     *
     * @return bool|int
     */
    public function addDevDependencies(array $devDependencies): bool|int
    {
        return $this->merge('devDependencies', $devDependencies)->write();
    }

    /**
     * @param array $dependencies
     *
     * @return bool|int
     */
    public function addDependencies(array $dependencies): bool|int
    {
        return $this->merge('dependencies', $dependencies)->write();
    }
}

<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Support\Filesystem;

use Illuminate\Support\Arr;

class JsonFile
{
    /**
     * The filename
     *
     * @var string
     */
    protected string $filename;

    /**
     * The file data
     *
     * @var array
     */
    protected array $data;

    /**
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;

        $this->read();
    }

    /**
     * Read the file data.
     * @return void
     */
    public function read(): void
    {
        $this->data = is_file($this->filename) ? json_decode(file_get_contents($this->filename), true) : [];
    }

    /**
     * Get a value using "dot" notation.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return array|mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * Check if an item exists using "dot" notation.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return Arr::has($this->data, $key);
    }

    /**
     * Set an item to a given value using "dot" notation.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set(string $key, mixed $value): static
    {
        Arr::set($this->data, $key, $value);
        return $this;
    }

    /**
     * Merge an item's value using "dot" notation.
     *
     * @param string $key
     * @param array $value
     *
     * @return $this
     */
    public function merge(string $key, array $value): static
    {
        return $this->set($key, array_merge($this->get($key, []), $value));
    }

    /**
     * Sort an item's value, using "dot" notation.
     *
     * @param string $key
     *
     * @return $this
     */
    public function sort(string $key): static
    {
        if ($this->has($key)) {
            if (is_array($value = $this->get($key))) {
                ksort($value);
                $this->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * Write the data to the file.
     *
     * @return bool|int
     */
    public function write(): bool|int
    {
        return file_put_contents($this->filename, json_encode($this->data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL);
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function filename(): string
    {
        return $this->filename;
    }
}

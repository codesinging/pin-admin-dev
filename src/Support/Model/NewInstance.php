<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Support\Model;

use JetBrains\PhpStorm\Pure;

trait NewInstance
{
    #[Pure]
    public static function new(array $attributes = []): static
    {
        return new static($attributes);
    }
}

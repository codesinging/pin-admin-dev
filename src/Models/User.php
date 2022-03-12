<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Models;

use CodeSinging\PinAdmin\Support\Model\AuthModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends AuthModel
{
    protected $fillable = [
        'name',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected function password(): Attribute
    {
        return new Attribute(
            set: fn($value) => bcrypt($value),
        );
    }
}

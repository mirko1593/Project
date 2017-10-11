<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = ['id'];

    protected static $instances = [
        'admin' => null,
        'representative' => null
    ];

    public static function admin()
    {
        if (! is_null(static::$instances['admin'])) {
            return static::$instances['admin'];
        }

        $instance = static::where('name', 'admin')->first();

        return static::$instances['admin'] = $instance;
    }

    public static function representative()
    {
        if (! is_null(static::$instances['representative'])) {
            return static::$instances['representative'];
        }

        $instance = static::where('name', 'representative')->first();

        return static::$instances['representative'] = $instance;
    }

    public function name()
    {
        return $this->name;
    }

    public function isRepresentative()
    {
        return $this->name() === 'representative';
    }

}

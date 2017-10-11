<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function actAs(Role $role)
    {
        if ($role->isRepresentative() && is_null($this->email)) {
            return false;
        }

        return $this->roles()->save($role);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin()
    {
        return $this->roles->pluck('name')->contains('admin');
    }

    public static function validates()
    {
        return [
            'name' => 'required',
            'phone' => 'required'
        ];
    }
}

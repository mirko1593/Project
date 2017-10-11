<?php

namespace App;

use DB;

class Representative
{
    public static function all()
    {
        $role = Role::representative();

        return DB::select("select * from users left join role_user on users.id == role_user.user_id where role_user.role_id = " . $role->id);
    }

    public static function create($user)
    {
        $user->actAs(Role::representative());

        return $user;
    }
}

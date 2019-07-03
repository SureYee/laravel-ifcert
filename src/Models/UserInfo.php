<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Model;


class UserInfo extends Model
{
    protected $table = 'ifcert_user_info';

    /**
     * @param $hash
     * @return self
     */
    public static function findByHash($hash)
    {
        return self::where('user_idcard_hash', $hash)->latest()->first();
    }
}
<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Model;


class UserInfo extends Model
{
    protected $table = 'ifcert_user_info';

    /*用户类型 1-自然人,2-企业*/
    const USER_TYPE_INDIVIDUAL = 1;
    const USER_TYPE_ENTERPRISE = 2;

    /*用户属性 1-出借方,2-借款方,3-出借方＋借款方,4-自代偿平台方,5-第三方代偿,6-受托支付方*/
    const USER_ATTR_LENDER = 1;
    const USER_ATTR_BORROWER = 2;
    const USER_ATTR_BOTH = 3;
    const USER_ATTR_SELF = 4;
    const USER_ATTR_3RD_PART = 5;
    const USER_ATTR_REPAY_AGENT = 6;

    /**
     * @param $hash
     * @return self
     */
    public static function findByHash($hash)
    {
        return self::where('user_idcard_hash', $hash)->latest()->first();
    }
}
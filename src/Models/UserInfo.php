<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;


/**
 * Class UserInfo
 * @package Sureyee\LaravelIfcert\Models
 * @property integer $user_type
 */
class UserInfo extends IfcertModel
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

    public static function getInfType()
    {
        return Request::INF_TYPE_USER_INFO;
    }

    public static function getTransformer()
    {
        return function (UserInfo $info) {
            return [
                'version' => Client::version(),
                'sourceCode' => config('ifcert.platform_code'),
                'userAttr' => $info->user_attr,
                'userType' => $info->user_type,
                'userCreateTime' => $info->user_create_time->format('Y-m-d H:i:s'),
                'userName' => $info->username,
                'countries' => $info->countries,
                'cardType' => $info->card_type,
                'userIdcardHash' => $info->user_idcard_hash,
                'userIdcard' => $info->user_idcard,
                'userPhone' => $info->user_phone,
                'userPhoneHash' => $info->user_phone_hash,
                'userUuid' => $info->user_uuid,
                'userLawperson' => $info->user_lawperson,
                'userFund' => $info->user_fund,
                'userProvince' => $info->user_province,
                'userAddress' => $info->user_address,
                'registerDate' => $info->regitser_date->format('Y-m-d H:i:s'),
                'userSex' => $info->user_sex,
                'userList' => array_map(function ($account) {
                    return [
                        'userBankAccount' => $account
                    ];
                }, $info->user_bank_account)
            ];
        };
    }

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        // TODO: Implement storeFromData() method.
    }
}
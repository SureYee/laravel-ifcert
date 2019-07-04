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
        return function (UserInfo $model) {
            return [
                'version' => Client::version(),
                'sourceCode' => config('ifcert.platform_code'),
                'userAttr' => $model->user_attr,
                'userType' => $model->user_type,
                'userCreateTime' => $model->user_create_time->format('Y-m-d H:i:s'),
                'userName' => $model->username,
                'countries' => $model->countries,
                'cardType' => $model->card_type,
                'userIdcardHash' => $model->user_idcard_hash,
                'userIdcard' => $model->user_idcard,
                'userPhone' => $model->user_phone,
                'userPhoneHash' => $model->user_phone_hash,
                'userUuid' => $model->user_uuid,
                'userLawperson' => $model->user_lawperson,
                'userFund' => $model->user_fund,
                'userProvince' => $model->user_province,
                'userAddress' => $model->user_address,
                'registerDate' => $model->regitser_date->format('Y-m-d H:i:s'),
                'userSex' => $model->user_sex,
                'userList' => array_map(function ($account) {
                    return [
                        'userBankAccount' => $account
                    ];
                }, $model->user_bank_account)
            ];
        };
    }

    public static function needReportData()
    {
        return self::unReport()->get();
    }
}
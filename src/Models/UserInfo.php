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
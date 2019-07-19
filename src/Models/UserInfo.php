<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;


/**
 * Class UserInfo
 * @package Sureyee\LaravelIfcert\Models
 * @property integer $user_type
 * @property $user_attr
 * @property Carbon $user_create_time
 * @property $username
 * @property $countries
 * @property $card_type
 * @property $user_idcard_hash
 * @property $user_idcard
 * @property $user_phone
 * @property $user_phone_hash
 * @property $user_uuid
 * @property $user_lawperson
 * @property $user_fund
 * @property $user_province
 * @property $user_address
 * @property null|Carbon $register_date
 * @property $user_sex
 * @property $user_bank_account
 */
class UserInfo extends IfcertModel
{


    protected $table = 'ifcert_user_info';

    protected $casts = [
        'user_bank_account' => 'array',
        'register_date' => 'datetime:Y-m-d'
    ];

    protected $dates = ['user_create_time'];

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
                'userLawperson' => $info->user_lawperson ?? -1,
                'userFund' => $info->user_fund ?? -1,
                'userProvince' => $info->user_province ?? -1,
                'userAddress' => $info->user_address ?? -1,
                'registerDate' => $info->register_date ? $info->register_date->format('Y-m-d H:i:s') : -1,
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
        $this->user_attr = $data['userAttr'];
        $this->user_type = $data['userType'];
        $this->user_create_time = $data['userCreateTime'];
        $this->username = $data['userName'];
        $this->countries = $data['countries'];
        $this->card_type = $data['cardType'];
        $this->user_idcard_hash = $data['userIdcardHash'];
        $this->user_idcard = $data['userIdcard'];
        $this->user_phone = $data['userPhone'];
        $this->user_phone_hash = $data['userPhoneHash'];
        $this->user_uuid = $data['userUuid'];
        $this->user_lawperson = $data['userLawperson'] != -1 ? $data['userLawperson'] : null;
        $this->user_province = $data['userProvince'] != -1 ? $data['userProvince'] : null;
        $this->user_address = $data['userAddress'] != -1 ? $data['userAddress'] : null;
        $this->register_date = $data['registerDate'] != -1 ? $data['registerDate'] : null;
        $this->user_sex = $data['userSex'] != -1 ? $data['userSex'] : null;
        $this->user_bank_account = isset($data['userBankAccount']) ? [$data['userBankAccount']] :array_map(function ($d) { return $d['userBankAccount']; }, $data['userList']);

        $this->request_id = $log->id;

        $this->save();
    }

    public function setUserBankAccountAttribute($value)
    {
        $this->attributes['user_bank_account'] = json_encode($value);
    }
}
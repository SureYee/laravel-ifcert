<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;

/**
 * Class Undertake
 * @package Sureyee\LaravelIfcert\Models
 * @property $un_fin_claim_id
 * @property $transfer_id
 * @property $fin_claim_id
 * @property $user_idcard_hash
 * @property $take_amount
 * @property $take_interest
 * @property $take_rate
 * @property $float_money
 * @property $take_time
 * @property $red_package
 * @property $lock_time
 */
class Undertake extends IfcertModel
{

    protected $table = 'ifcert_undertake_info';

    protected $dates = ['take_time', 'lock_time'];

    public static function getInfType()
    {
        return Request::INF_TYPE_UNDER_TAKE;
    }

    public function getTakeAmountAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getTakeInterestAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getFloatMoneyAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getRedPackageAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getTakeRateAttribute($value)
    {
        return sprintf('%01.6f', $value);
    }

    public static function getTransformer()
    {
        return function (Undertake $undertake) {
            return [
                'version' => Client::version(),
                'sourceCode' => $undertake->getSourceCode(),
                'unFinClaimId' => $undertake->un_fin_claim_id,
                'transferId' => $undertake->transfer_id,
                'finClaimId' => $undertake->fin_claim_id,
                'userIdcardHash' => $undertake->user_idcard_hash,
                'takeAmount' => $undertake->take_amount,
                'takeInterest' => $undertake->take_interest,
                'takeRate' => $undertake->take_rate,
                'floatMoney' => $undertake->float_money,
                'takeTime' => $undertake->take_time->format('Y-m-d H:i:s'),
                'redpackage' => $undertake->red_package,
                'lockTime' => $undertake->lock_time->format('Y-m-d')
            ];
        };
    }

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        $this->un_fin_claim_id = $data['unfinClaimId'];
        $this->transfer_id = $data['transferId'];
        $this->fin_claim_id = $data['finClaimId'];
        $this->user_idcard_hash = $data['userIdcardHash'];
        $this->take_amount = $data['takeAmount'];
        $this->take_interest = $data['takeInterest'];
        $this->take_rate = $data['takeRate'];
        $this->float_money = $data['floatMoney'];
        $this->take_time = $data['takeTime'];
        $this->red_package = $data['redpackage'];
        $this->lock_time = $data['lockTime'];

        $this->request_id = $log->id;
        $this->save();
    }
}
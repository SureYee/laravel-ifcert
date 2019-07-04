<?php

namespace Sureyee\LaravelIfcert\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;

/**
 * Class TransferProject
 * @package Sureyee\LaravelIfcert\Models
 * @property $transfer_id
 * @property $from_type
 * @property $fin_claim_id
 * @property $user_idcard_hash
 * @property $source_product_code
 * @property $transfer_amount
 * @property $transfer_interest_rate
 * @property $float_money
 * @property Carbon $transfer_date
 */
class TransferProject extends IfcertModel
{


    protected $table = 'ifcert_transfer_projects';

    protected $dates = ['transfer_date'];

    public static function getInfType()
    {
        return Request::INF_TYPE_TRANSFER_PROJECT;
    }

    public static function getTransformer()
    {
        return function (TransferProject $transfer) {
            return [
                'version' => Client::version(),
                'sourceCode' => $transfer->getSourceCode(),
                'transferId' => $transfer->transfer_id,
                'fromType' => $transfer->from_type,
                'finClaimId' => $transfer->fin_claim_id,
                'userIdcardHash' => $transfer->user_idcard_hash,
                'sourceProductCode' => $transfer->source_product_code,
                'transferAmount' => $transfer->transfer_amount,
                'transferInterestRate' => $transfer->transfer_interest_rate,
                'floatMoney' => $transfer->float_money,
                'transferDate' => $transfer->transfer_date->format('Y-m-d'),
            ];
        };
    }

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        $this->transfer_id = $data['transferId'];
        $this->from_type = $data['fromType'];
        $this->fin_claim_id = $data['finClaimId'];
        $this->user_idcard_hash = $data['userIdcardHash'];
        $this->source_product_code = $data['sourceProductCode'];
        $this->transfer_amount = $data['transferAmount'];
        $this->transfer_interest_rate = $data['transferInterestRate'];
        $this->float_money = $data['floatMoney'];
        $this->transfer_date = $data['transferDate'];
        $this->request_id = $log->id;

        $this->save();
    }

    public function getTransferAmountAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getTransferInterestRateAttribute($value)
    {
        return sprintf('%01.6f', $value);
    }

    public function getFloatMoneyAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

}
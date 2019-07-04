<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;


/**
 * Class Transact
 * @package Sureyee\LaravelIfcert\Models
 * @property $source_product_code
 * @property $trans_id
 * @property $source_product_name
 * @property $user_idcard_hash
 * @property $trans_money
 * @property $trans_type
 * @property $transfer_id
 * @property $fin_claim_id
 * @property Carbon $trans_time
 */
class Transact extends IfcertModel
{

    protected $table = 'ifcert_transact';

    protected $dates = ['trans_time'];

    public static function getInfType()
    {
        return Request::INF_TYPE_TRANSACT;
    }

    public static function getTransformer()
    {
        return function (Transact $transact) {
            return [
                'version' => Client::version(),
                'sourceCode' => $transact->getSourceCode(),
                'transId' => $transact->trans_id,
                'sourceProductCode' => $transact->source_product_code,
                'sourceProductName' => $transact->source_product_name,
                'transMoney' => $transact->trans_money,
                'transTime' => $transact->trans_time->format('Y-m-d H:i:s'),
                'userIdcardHash' => $transact->user_idcard_hash,
                'transferId' => $transact->transfer_id,
                'transType' => $transact->trans_type,
                'finClaimId' => $transact->fin_claim_id,
            ];
        };
    }

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        $this->trans_id = $data['transId'];
        $this->source_product_code = $data['sourceProductCode'];
        $this->source_product_name = $data['sourceProductName'];
        $this->trans_money = $data['transMoney'];
        $this->user_idcard_hash = $data['userIdcardHash'];
        $this->trans_time = $data['transTime'];
        $this->transfer_id = $data['transferId'];
        $this->trans_type = $data['transType'];
        $this->fin_claim_id = $data['finClaimId'];
        $this->request_id = $log->id;

        $this->save();
    }

    public function getTransMoneyAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }
}
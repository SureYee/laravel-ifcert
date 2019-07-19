<?php

namespace Sureyee\LaravelIfcert\Models;

use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;

/**
 * Class Creditor
 * @package Sureyee\LaravelIfcert\Models
 * @property $fin_claim_id
 * @property $source_product_code
 * @property $user_idcard_hash
 * @property $inv_amount
 * @property $inv_rate
 * @property $inv_time
 * @property $red_package
 * @property $lock_time
 */
class Creditor extends IfcertModel
{

    protected $table = 'ifcert_creditor';

    protected $dates = ['inv_time', 'lock_time'];

    public static function getInfType()
    {
        return Request::INF_TYPE_CREDITOR;
    }

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        $this->fin_claim_id = $data['finClaimId'];
        $this->source_product_code = $data['sourceProductCode'];
        $this->user_idcard_hash = $data['userIdcardHash'];
        $this->inv_amount = $data['invAmount'];
        $this->inv_rate = $data['invRate'];
        $this->inv_time = $data['invTime'];
        $this->red_package = $data['redpackage'];
        $this->lock_time = $data['lockTime'];
        $this->request_id = $log->id;

        return $this->save();
    }

    /**
     * 将数据库数据转化为上报数据结构
     */
    public static function getTransformer()
    {
        return function (Creditor $creditor) {
            return [
                'version' => Client::version(),
                'sourceCode' => $creditor->getSourceCode(),
                'finClaimId' => $creditor->fin_claim_id,
                'sourceProductCode' => $creditor->source_product_code,
                'userIdcardHash' => $creditor->user_idcard_hash,
                'invAmount' => $creditor->inv_amount,
                'invRate' => $creditor->inv_rate,
                'invTime' => $creditor->inv_time->format('Y-m-d H:i:s'),
                'redpackage' => $creditor->red_package,
                'lockTime' =>  $creditor->lock_time->format('Y-m-d'),
            ];
        };
    }

    public function getRedPackageAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getInvAmountAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getInvRateAttribute($value)
    {
        return sprintf('%01.6f', $value);
    }
}
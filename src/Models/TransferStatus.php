<?php

namespace Sureyee\LaravelIfcert\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;


/**
 * Class TransferStatus
 * @package Sureyee\LaravelIfcert\Models
 * @property $transfer_id
 * @property $transfer_status
 * @property $amount
 * @property $float_money
 * @property Carbon $product_date
 */
class TransferStatus extends IfcertModel
{
    protected $table = 'ifcert_transfer_status';

    protected $dates = ['product_date'];

    public static function getInfType()
    {
        return Request::INF_TYPE_TRANSFER_STATUS;
    }


    public static function getTransformer()
    {
        return function (TransferStatus $status) {
            return [
                'version' => Client::version(),
                'sourceCode' => $status->getSourceCode(),
                'transferId' => $status->transfer_id,
                'transferStatus' => $status->transfer_status,
                'amount' => $status->amount,
                'floatMoney' => $status->float_money,
                'productDate' => $status->product_date->format('Y-m-d H:i:s'),
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
        $this->transfer_status = $data['transferStatus'];
        $this->amount = $data['amount'];
        $this->float_money = $data['floatMoney'];
        $this->product_date = $data['productDate'];
        $this->request_id = $log->id;

        return $this->save();
    }

    public function getAmountAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getFloatMoneyAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }
}
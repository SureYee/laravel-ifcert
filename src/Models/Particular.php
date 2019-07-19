<?php

namespace Sureyee\LaravelIfcert\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;

/**
 * Class Particular
 * @package Sureyee\LaravelIfcert\Models
 * @property string $trans_id
 * @property string $source_financing_code
 * @property int $trans_type
 * @property float $trans_money
 * @property string $user_idcard_hash
 * @property Carbon $trans_time
 */
class Particular extends IfcertModel
{
    protected $table = 'ifcert_particulars';

    protected $dates = ['trans_time'];

    public static function getInfType()
    {
        return Request::INF_TYPE_LEND_PARTICULARS;
    }

    public static function getTransformer()
    {
        return function (Particular $particular) {
            return [
                'version' => Client::version(),
                'sourceCode' => config('ifcert.platform_code'),
                'transId' =>  $particular->trans_id,
                'sourceFinancingCode' => $particular->source_financing_code,
                'transType' => $particular->trans_type,
                'transMoney' => $particular->trans_money,
                'userIdcardHash' => $particular->user_idcard_hash,
                'transTime' => $particular->trans_time->format('Y-m-d H:i:s'),
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
         $this->source_financing_code = $data['sourceFinancingCode'];
         $this->trans_type = $data['transType'];
         $this->trans_money = $data['transMoney'];
         $this->user_idcard_hash = $data['userIdcardHash'];
         $this->trans_time = $data['transTime'];
         $this->request_id = $log->id;
         $this->save();
    }

    public function getTransMoneyAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }
}
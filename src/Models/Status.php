<?php

namespace Sureyee\LaravelIfcert\Models;

use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;


/**
 * Class Status
 * @package Sureyee\LaravelIfcert\Models
 * @property $source_product_code
 * @property $product_status
 * @property $product_date
 */
class Status extends IfcertModel
{

    protected $table = 'ifcert_status';

    protected $dates = ['product_date'];

    public static function getInfType()
    {
        return Request::INF_TYPE_STATUS;
    }

    public static function getTransformer()
    {
        return function (Status $status) {
            return [
                'version' => Client::version(),
                'sourceCode' => $status->getSourceCode(),
                'sourceProductCode' => $status->source_product_code,
                'productStatus' => $status->product_status,
                'productDate' => $status->product_date->format('Y-m-d H:i:s')
            ];
        };
    }
    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        $this->product_date = $data['productDate'];
        $this->product_status = $data['productStatus'];
        $this->source_product_code = $data['sourceProductCode'];
        $this->request_id = $log->id;

        $this->save();
    }
}
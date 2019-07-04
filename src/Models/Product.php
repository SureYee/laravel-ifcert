<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;


class Product extends IfcertModel
{

    protected $table = 'ifcert_products';

    public static function getInfType()
    {
        return Request::INF_TYPE_LEND_PRODUCT;
    }

    public static function getTransformer()
    {
        // TODO: Implement getTransformer() method.
    }

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        // TODO: Implement storeFromData() method.
    }
}
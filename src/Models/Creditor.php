<?php

namespace Sureyee\LaravelIfcert\Models;

use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;


class Creditor extends IfcertModel
{

    protected $table = 'ifcert_creditor';

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

    }

    /**
     * 将数据库数据转化为上报数据结构
     * @return array
     */
    public static function getTransformer()
    {
        // TODO: Implement getTransformer() method.
    }
}
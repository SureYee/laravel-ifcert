<?php

namespace Sureyee\LaravelIfcert\Models;

use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;

class RepayPlan extends IfcertModel
{


    protected $table = 'ifcert_repay_plan';

    public static function getInfType()
    {
        return Request::INF_TYPE_REPAY_PLAN;
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
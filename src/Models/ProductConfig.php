<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;


class ProductConfig extends IfcertModel
{

    protected $table = 'ifcert_product_config';

    public static function getInfType()
    {
        return Request::INF_TYPE_LEND_PRODUCT_CONFIG;
    }

    public static function getTransformer()
    {
        // TODO: Implement getTransformer() method.
    }

    public static function needReportData()
    {
        return self::unReport()->get();
    }
}
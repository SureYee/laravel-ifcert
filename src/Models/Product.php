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
     * @return \Illuminate\Support\Collection
     */
    public static function needReportData()
    {
        return self::unReport()->get();
    }
}
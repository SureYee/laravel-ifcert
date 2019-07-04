<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;



class Undertake extends IfcertModel
{


    protected $table = 'ifcert_undertake_info';

    public static function getInfType()
    {
        return Request::INF_TYPE_UNDER_TAKE;
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
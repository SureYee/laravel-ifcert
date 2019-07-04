<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;



class ScatterInvest extends IfcertModel
{


    protected $table = 'ifcert_scatter_invest';

    public static function getInfType()
    {
        return Request::INF_TYPE_SCATTER_INVEST;
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
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

    public static function getTransformer()
    {
        // TODO: Implement getTransformer() method.
    }

    public static function needReportData()
    {
        return self::unReport()->get();
    }
}
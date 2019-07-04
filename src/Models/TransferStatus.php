<?php

namespace Sureyee\LaravelIfcert\Models;

use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;



class TransferStatus extends IfcertModel
{


    protected $table = 'ifcert_transfer_status';

    public static function getInfType()
    {
        return Request::INF_TYPE_TRANSFER_STATUS;
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
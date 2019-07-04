<?php

namespace Sureyee\LaravelIfcert\Contracts;


use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Requests\BatchRequest;
use Sureyee\LaravelIfcert\Models\RequestLog;

abstract class IfcertModel extends Model
{
    abstract public static function getInfType();

    abstract public static function getTransformer();

    abstract public static function needReportData();

    public function scopeUnReport($query)
    {
        return $query->whereNull('request_id');
    }

    public function updateReported(BatchRequest $request, RequestLog $log)
    {
        $request->getDataList()->each(function (IfcertModel $model) use ($log) {
            $model->request()->associate($log);
            $model->save();
        });
    }

    public function request()
    {
        return $this->belongsTo(RequestLog::class, 'request_id');
    }
}
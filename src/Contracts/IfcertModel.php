<?php

namespace Sureyee\LaravelIfcert\Contracts;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Requests\BatchRequest;
use Sureyee\LaravelIfcert\Models\RequestLog;

/**
 * Class IfcertModel
 * @package Sureyee\LaravelIfcert\Contracts
 * @property RequestLog $request
 * @property int $request_id
 * @method static Builder unReport()
 * @method static Builder needCheck()
 */
abstract class IfcertModel extends Model
{
    abstract public static function getInfType();

    /**
     * 将数据库数据转化为上报数据结构
     * @return array
     */
    abstract public static function getTransformer();

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    abstract public function storeFromData(array $data, RequestLog $log);

    public static function needReportData()
    {
        return self::unReport()->get();
    }

    public function getSourceCode()
    {
        return config('ifcert.platform_code');
    }

    public function scopeUnReport($query)
    {
        return $query->whereNull('request_id');
    }

    public function scopeNeedCheck($query)
    {
        return $query->where('checked_message', '<>', 'success');
    }

    public function updateReported(BatchRequest $request, RequestLog $log)
    {
        $request->getDataList()->each(function ($data) use ($request, $log) {
            if ($data instanceof IfcertModel) {
                $data->request()->associate($log);
                $data->save();
            } else {
                $request->getModel()->storeFromData((array) $data, $log);
            }
        });
    }

    public function request()
    {
        return $this->belongsTo(RequestLog::class, 'request_id');
    }
}
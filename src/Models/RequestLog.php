<?php

namespace Sureyee\LaravelIfcert\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Requests\BatchRequest;
use Sureyee\LaravelIfcert\Responses\Response;

/**
 * Class RequestLog
 * @package Sureyee\LaravelIfcert\Models
 * @property string batch_num
 * @property Carbon send_time
 * @property integer inf_type
 * @property string url
 * @property integer count
 * @property array request_data
 * @property boolean has_checked
 * @property boolean has_reported
 * @property boolean error_code
 * @property boolean error_message
 *
 */
class RequestLog extends Model
{
    protected $table = 'ifcert_request_logs';

    protected $casts= [
        'send_time' => 'datetime',
        'request_data' => 'array'
    ];

    public static function logRequest(Request $request) {

        if (!($request instanceof BatchRequest)) return;
        $model = new self();

        $model->batch_num = $request->getBatchNumber();
        $model->send_time = $request->getSendTime();
        $model->inf_type = $request->getInfType();
        $model->url = $request->getUrl();
        $model->count = $request->getCount();
        $model->request_data = $request->getData();

        $model->save();
    }

    public function setReported()
    {
        $this->has_reported = 1;
        return $this;
    }

    public function setChecked()
    {
        $this->has_checked = 1;
        return $this;
    }

    public function setError(Response $response)
    {
        $this->error_code = $response->getCode();
        $this->error_message = $response->getMessage();

        return $this;
    }

    /**
     * @param $batchNum
     * @return $this
     */
    public static function findByBatchNum($batchNum)
    {
        return self::where('batch_num', $batchNum)->first();
    }
}
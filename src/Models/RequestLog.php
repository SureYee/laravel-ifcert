<?php

namespace Sureyee\LaravelIfcert\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Requests\BatchRequest;
use Sureyee\LaravelIfcert\Responses\Response;

/**
 * Class RequestLog
 * @package Sureyee\LaravelIfcert\Models
 * @property $id
 * @property string $batch_num
 * @property Carbon $send_time
 * @property integer $inf_type
 * @property string $url
 * @property integer $count
 * @property boolean $has_checked
 * @property int $data_type
 * @property boolean $has_reported
 * @property boolean $error_code
 * @property boolean $error_message
 *
 * @method static Builder unChecked()
 * @method static Builder not()
 * @method static Builder success()
 * @method static Builder hold()
 * @method static Builder failed()
 */
class RequestLog extends Model
{
    protected $table = 'ifcert_request_logs';

    protected $casts= [
        'send_time' => 'datetime',
    ];

    /**
     * 记录请求
     * @param Request $request
     * @return mixed
     */
    public static function logRequest(BatchRequest $request) {

        DB::transaction(function () use($request) {
            $model = new self();

            $model->batch_num = $request->getBatchNumber();
            $model->send_time = $request->getSendTime();
            $model->inf_type = $request->getInfType();
            $model->data_type = $request->getDataType();
            $model->url = $request->getUrl();
            $model->count = $request->getCount();

            $model->save();

            $request->getModel()->updateReported($request, $model);
        });

        return true;
    }

    /**
     * 设置已经推送
     * @return $this
     */
    public function setReported()
    {
        $this->has_reported = 1;
        return $this;
    }

    /**
     * 设置已经对账
     * @return $this
     */
    public function setChecked()
    {
        $this->has_checked = 1;
        return $this;
    }

    /**
     * 设置错误信息
     * @param Response $response
     * @return $this
     */
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

    public function scopeUnChecked($query)
    {
        return $query->where('has_reported', 1)->where('has_checked', 0);
    }

    public function scopeNot($query)
    {
        return $query->where('checked_message', 'isNot');
    }

    public function scopeSuccess($query)
    {
        return $query->where('checked_message', 'success');
    }

    public function scopeHold($query)
    {
        return $query->where('checked_message', 'hold');
    }

    public function scopeFailed($query)
    {
        return $query->where('checked_message', 'failed');
    }
}
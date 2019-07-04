<?php

namespace Sureyee\LaravelIfcert\Models;

use Carbon\Carbon;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;

/**
 * Class RepayPlan
 * @package Sureyee\LaravelIfcert\Models
 * @property $source_product_code
 * @property $user_idcard_hash
 * @property $total_issue
 * @property $issue
 * @property $replan_id
 * @property $cur_fund
 * @property $cur_interest
 * @property $cur_service_charge
 * @property Carbon $repay_time
 */
class RepayPlan extends IfcertModel
{
    protected $table = 'ifcert_repay_plan';

    protected $dates = ['repay_time'];

    public static function getInfType()
    {
        return Request::INF_TYPE_REPAY_PLAN;
    }

    public static function getTransformer()
    {
       return function (RepayPlan $plan) {
           return [
               'version' => Client::version(),
               'sourceCode' => $plan->getSourceCode(),
               'sourceProductCode' => $plan->source_product_code,
               'userIdcardHash' => $plan->user_idcard_hash,
               'totalIssue' => $plan->total_issue,
               'issue' => $plan->issue,
               'replanId' => $plan->replan_id,
               'curFund' => $plan->cur_fund,
               'curInterest' => $plan->cur_interest,
               'curServiceCharge' => $plan->cur_service_charge,
               'repayTime' => $plan->repay_time->format('Y-m-d H:i:s'),
           ];
       };
    }

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        $this->source_product_code = $data['sourceProductCode'];
        $this->user_idcard_hash = $data['userIdcardHash'];
        $this->total_issue = $data['totalIssue'];
        $this->issue = $data['issue'];
        $this->replan_id = $data['replanId'];
        $this->cur_fund = $data['curFund'];
        $this->cur_interest = $data['curInterest'];
        $this->cur_service_charge = $data['curServiceCharge'];
        $this->repay_time = $data['repayTime'];
        $this->request_id = $log->id;

        $this->save();
    }

    public function getCurFundAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getCurInterestAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getCurServiceChargeAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }
}
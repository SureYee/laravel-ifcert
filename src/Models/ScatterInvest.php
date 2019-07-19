<?php

namespace Sureyee\LaravelIfcert\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;

/**
 * Class ScatterInvest
 * @package Sureyee\LaravelIfcert\Models
 * @property Carbon $product_start_time
 * @property string $product_name
 * @property $source_product_code
 * @property $user_idcard_hash
 * @property $loan_use
 * @property $loan_describe
 * @property $loan_rate
 * @property $amount
 * @property $surplus_amount
 * @property $term
 * @property $pay_type
 * @property $service_cost
 * @property $loan_type
 * @property $security_type
 * @property $security_company_amount
 * @property $security_company_name
 * @property $security_company_idcard
 * @property $is_financing_assure
 * @property $security_amount
 * @property $project_source
 */
class ScatterInvest extends IfcertModel
{

    protected $table = 'ifcert_scatter_invest';

    protected $dates = ['product_start_time'];

    public static function getInfType()
    {
        return Request::INF_TYPE_SCATTER_INVEST;
    }


    public static function getTransformer()
    {
        return function (ScatterInvest $scatterInvest) {
            return [
                'version' => Client::version(),
                'sourceCode' => $scatterInvest->getSourceCode(),
                "productStartTime" => $scatterInvest->product_start_time->format('Y-m-d H:i:s'),
                "productName" => $scatterInvest->product_name,
                "sourceProductCode" => $scatterInvest->source_product_code,
                "userIdcardHash" => $scatterInvest->user_idcard_hash,
                "loanUse" => $scatterInvest->loan_use,
                "loanDescribe" => $scatterInvest->loan_describe,
                "loanRate" => $scatterInvest->loan_rate,
                "amount" => $scatterInvest->amount,
                "surplusAmount" => $scatterInvest->surplus_amount,
                "term" => $scatterInvest->term,
                "payType" => $scatterInvest->pay_type,
                "serviceCost" => $scatterInvest->service_cost,
                "loanType" => $scatterInvest->loan_type,
                "securityType" => $scatterInvest->security_type,
                "securityCompanyAmount" => $scatterInvest->security_company_amount,
                "securityCompanyName" => $scatterInvest->security_company_name,
                "securityCompanyIdcard" => $scatterInvest->security_company_idcard,
                "isFinancingAssure" => $scatterInvest->is_financing_assure,
                "securityAmount" => $scatterInvest->security_amount,
                "projectSource" => $scatterInvest->project_source
            ];
        };
    }

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        $this->product_start_time = $data['productStartTime'];
        $this->product_name = $data['productName'];
        $this->source_product_code = $data['sourceProductCode'];
        $this->user_idcard_hash = $data['userIdcardHash'];
        $this->loan_use = $data['loanUse'];
        $this->loan_describe = $data['loanDescribe'];
        $this->loan_rate = $data['loanRate'];
        $this->amount = $data['amount'];
        $this->surplus_amount = $data['surplusAmount'];
        $this->term = $data['term'];
        $this->pay_type = $data['payType'];
        $this->service_cost = $data['serviceCost'];
        $this->loan_type = $data['loanType'];
        $this->security_type = $data['securityType'];
        $this->security_company_amount = $data['securityCompanyAmount'];
        $this->security_company_name = $data['securityCompanyName'];
        $this->security_company_idcard = $data['securityCompanyIdcard'];
        $this->is_financing_assure = $data['isFinancingAssure'];
        $this->security_amount = $data['securityAmount'];
        $this->project_source = $data['projectSource'];
        $this->request_id = $log->id;

        return $this->save();
    }

    public function getAmountAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getLoanRateAttribute($value)
    {
        return sprintf('%01.6f', $value);
    }

    public function getSurplusAmountAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getServiceCostAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }

    public function getSecurityAmountAttribute($value)
    {
        return sprintf('%01.2f', $value);
    }
}
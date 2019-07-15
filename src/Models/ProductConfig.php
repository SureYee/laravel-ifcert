<?php

namespace Sureyee\LaravelIfcert\Models;

use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;


class ProductConfig extends IfcertModel
{

    protected $table = 'ifcert_product_config';

    public static function getInfType()
    {
        return Request::INF_TYPE_LEND_PRODUCT_CONFIG;
    }

    public static function getTransformer()
    {
        return function (ProductConfig $config) {
            return [
                'version' => Client::version(),
                'sourceCode' => config('ifcert.platform_code'),
                'configId' => $config->config_id,
                'finClaimId' => $config->fin_claim_id,
                'sourceFinancingCode' => $config->source_financing_code,
//                'sourceProductCode' => $config->source_product_code,
                'userIdcardHash' => $config->user_idcard_hash
            ];
        };
    }

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        $this->config_id = $data['configId'];
        $this->request_id = $log->id;
        $this->fin_claim_id = $data['finClaimId'];
        $this->source_financing_code = $data['sourceFinancingCode'];
//        $this->source_product_code = $data['sourceProductCode'];
        $this->user_idcard_hash = $data['userIdcardHash'];

        return $this->save();
    }
}
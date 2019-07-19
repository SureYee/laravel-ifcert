<?php

namespace Sureyee\LaravelIfcert\Models;

use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\IfcertModel;
use Sureyee\LaravelIfcert\Contracts\Request;

/**
 * Class Product
 * @package Sureyee\LaravelIfcert\Models
 * @property $source_financing_code
 * @property $financing_start_time
 * @property $product_name
 * @property $rate
 * @property $min_rate
 * @property $max_rate
 * @property $term
 * @property $request_id
 */
class Product extends IfcertModel
{

    protected $table = 'ifcert_products';

    public static function getInfType()
    {
        return Request::INF_TYPE_LEND_PRODUCT;
    }

    public static function getTransformer()
    {
        return function (Product $product) {
            return [
                'version' => Client::version(),
                'sourceCode' => config('ifcert.platform_code'),
                'sourceFinancingCode' => $product->source_financing_code,
                'financingStartTime' => $product->financing_start_time,
                'productName' => $product->product_name,
                'rate' => $product->rate,
                'minRate' => $product->min_rate,
                'maxRate' => $product->max_rate,
                'term' => $product->term
            ];
        };
    }

    /**
     * 将上报的数据转化为数据库数据结构
     * @return mixed
     */
    public function storeFromData(array $data, RequestLog $log)
    {
        $this->source_financing_code = $data['sourceFinancingCode'];
        $this->financing_start_time = $data['financingStartTime'];
        $this->product_name = $data['productName'];
        $this->rate = $data['rate'];
        $this->min_rate = $data['minRate'];
        $this->max_rate = $data['maxRate'];
        $this->term = $data['term'];
        $this->request_id = $log->id;

        return $this->save();
    }

    public function getRateAttribute($value)
    {
        return sprintf('%01.6f', $value);
    }

    public function getMinRateAttribute($value)
    {
        return is_null($value) || $value < 0 ? -1 : sprintf('%01.6f', $value);
    }

    public function getMaxRateAttribute($value)
    {
        return is_null($value) || $value < 0 ? -1 : sprintf('%01.6f', $value);
    }
}
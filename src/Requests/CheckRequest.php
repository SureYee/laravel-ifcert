<?php

namespace Sureyee\LaravelIfcert\Requests;

use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Exceptions\CertException;
use Sureyee\LaravelIfcert\Models\RequestLog;

/**
 * 对账接口请求类
 * Class CheckRequest
 * @package Sureyee\LaravelIfcert\Requests
 */
class CheckRequest extends Request
{
    const REQUEST_TYPE_BATCH_MESSAGE = 'batchMessage';
    const REQUEST_TYPE_BATCH_NUM = 'batchNum';
    const REQUEST_TYPE_BATCH_LIST = 'batchList';

    protected static $url = 'https://api.ifcert.org.cn/balanceService/v15';

    protected $requestType;

    protected $requestData;

    /**
     * CheckRequest constructor.
     * @param array|RequestLog $requestData
     * @param int $requestType
     * @throws CertException
     */
    public function __construct($requestData, $requestType)
    {
        if ($requestData instanceof RequestLog) {
            $requestData = [
                'batchNum' => $requestData->batch_num,
                'infType' => $requestData->inf_type
            ];
        }

        if (!array_key_exists('infType', $requestData))
            throw new CertException('infType参数有误');

        if (!in_array($requestType, [self::REQUEST_TYPE_BATCH_LIST, self::REQUEST_TYPE_BATCH_MESSAGE, self::REQUEST_TYPE_BATCH_NUM]))
            throw new CertException('未知的请求类型');

        $this->requestData = $requestData;
        $this->requestType = $requestType;

        parent::__construct($requestData['infType']);

    }

    public function getUrl()
    {
        return self::$url . '/' . $this->requestType . '?' . http_build_query($this->getData());
    }

    public function getData()
    {
        $data = [
            'apiKey' => $this->getApiKey(),
            'dataType' => $this->dataType,
            'timestamp' => $this->timestamp,
            'nonce' => $this->nonce,
            'sourceCode' => $this->sourceCode,
            'version' => Client::version(),
        ];

        return array_merge($this->requestData, $data);
    }

    public function getMethod()
    {
        return 'GET';
    }

    public function getBody()
    {
        return null;
    }

    public function getHeaders()
    {
        return [];
    }
}
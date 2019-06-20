<?php

namespace Sureyee\LaravelIfcert\Requests;

use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Exceptions\CertException;

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
     * @param $requestData
     * @param $requestType
     * @throws CertException
     */
    public function __construct($requestData, $requestType)
    {
        $this->requestData = $requestData;

        if (!in_array($requestType, [self::REQUEST_TYPE_BATCH_LIST, self::REQUEST_TYPE_BATCH_MESSAGE, self::REQUEST_TYPE_BATCH_NUM]))
            throw new CertException('未知的请求类型');

        $this->requestType = $requestType;

    }

    public function getUrl()
    {
        return self::$url . '/' . $this->requestType;
    }

    public function getData()
    {
        $data = [
            'apiKey' => $this->getApiKey(),
            'dataType' => $this->dataType,
            'timestamp' => $this->timestamp,
            'nonce' => $this->nonce,
            'sourceCode' => $this->sourceCode,
            'sentDate' => $this->sendTime,
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
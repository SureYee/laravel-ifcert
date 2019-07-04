<?php

namespace Sureyee\LaravelIfcert\Contracts;


use Carbon\Carbon;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Tools;

abstract class Request
{
    const INF_TYPE_USER_INFO = 1;
    const INF_TYPE_SCATTER_INVEST = 2;
    const INF_TYPE_STATUS = 6;
    const INF_TYPE_REPAY_PLAN = 81;
    const INF_TYPE_CREDITOR = 82;
    const INF_TYPE_TRANSFER_PROJECT = 83;
    const INF_TYPE_TRANSFER_STATUS = 84;
    const INF_TYPE_UNDER_TAKE = 85;
    const INF_TYPE_TRANSACT = 4;
    const INF_TYPE_LEND_PRODUCT = 86;
    const INF_TYPE_LEND_PRODUCT_CONFIG = 87;
    const INF_TYPE_LEND_PARTICULARS = 88;

    /**
     * 生成好的apikey
     * @var string
     */
    protected $apiKey;

    /**
     * 加密时间戳
     * @var string
     */
    protected $timestamp;

    /**
     * 加密随机字符串
     * @var string
     */
    protected $nonce;

    /**
     * 数据类型，调试为0，正式为1
     * @var
     */
    protected $dataType;

    /**
     * 平台编码
     * @var string
     */
    protected $sourceCode;

    /**
     * 发送时间
     * @var string
     */
    protected $sendTime;

    /**
     * 接口类型
     * @var int
     */
    protected $infType;

    /**
     * 批次号
     * @var string
     */
    protected $batchNumber;

    /**
     * 请求数据条数
     * @var int
     */
    protected $count = 0;

    public function __construct($infType)
    {
        $this->sourceCode = config('ifcert.platform_code');

        $this->setApiKey();

        $this->dataType = config('ifcert.enter_db', 0);

        $this->infType = $infType;

        $this->setSendTime(Carbon::now());
    }


    public function setApiKey()
    {
        $apiKey = Tools::getApiKey(config('ifcert.api_key'), config('ifcert.platform_code'), Client::version());
        $this->timestamp = $apiKey['timestamp'];
        $this->apiKey = $apiKey['apiKey'];
        $this->nonce = $apiKey['nonce'];
        return $this;
    }

    public function setSendTime($dateTime)
    {
        $this->sendTime = $dateTime;
        return $this;
    }

    /**
     * 手动调用设置batchnumber
     * @param $tradeDate
     * @param $tradNum
     * @return $this
     * @throws \Sureyee\LaravelIfcert\Exceptions\CertException
     */
    public function setBatchNumber($tradeDate, $tradNum)
    {
        $this->batchNumber = Tools::batchNumber($this->sourceCode, $tradeDate, $tradNum);
        return $this;
    }

    /**
     * 获取加密后的apikey
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * 获取发送时间
     * @return string
     */
    public function getSendTime()
    {
        return $this->sendTime;
    }

    /**
     * 检测是否是生产环境
     * @return bool
     */
    protected function isProduction()
    {
        return (Client::env() === 'production' || Client::env() === 'prod');
    }

    public function getBatchNumber()
    {
        return $this->batchNumber;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getInfType()
    {
        return $this->infType;
    }

    abstract public function getUrl();

    abstract public function getData();

    abstract public function getMethod();

    abstract public function getBody();

    abstract public function getHeaders();

}
<?php

namespace Sureyee\LaravelIfcert\Requests;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\TransformerInterface;
use Sureyee\LaravelIfcert\Exceptions\CertException;
use Sureyee\LaravelIfcert\Tools;

class Request
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
    const INF_TYPE_LEND_PARTICULARS = 85;

    protected static $infTypes = [
        self::INF_TYPE_USER_INFO => 'userinfo',
        self::INF_TYPE_SCATTER_INVEST => 'scatterInvest',
        self::INF_TYPE_STATUS => 'status',
        self::INF_TYPE_REPAY_PLAN => 'repayPlan',
        self::INF_TYPE_CREDITOR => 'creditor',
        self::INF_TYPE_TRANSFER_PROJECT => 'transferProject',
        self::INF_TYPE_TRANSFER_STATUS => 'transferStatus',
        self::INF_TYPE_UNDER_TAKE => 'underTake',
        self::INF_TYPE_TRANSACT => 'transact',
        self::INF_TYPE_LEND_PRODUCT => 'lendProduct',
        self::INF_TYPE_LEND_PRODUCT_CONFIG => 'lendProductConfig',
        self::INF_TYPE_LEND_PARTICULARS => 'lendParticulars',
    ];

    protected static $url = 'https://api.ifcert.org.cn/p2p';

    /**
     * 批次数据
     * @var Collection
     */
    protected $batchData;

    /**
     * transformer
     * @var \Closure|TransformerInterface|null
     */
    protected $transformer;

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
     * @var \Illuminate\Config\Repository|mixed
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
     * Request constructor.
     * @param array|Collection $batchData
     * @param integer $infType
     * @param \Closure|TransformerInterface $transformer
     */
    public function __construct($batchData, $infType, $transformer = null)
    {
        $this->batchData = $batchData instanceof Collection ? $batchData : collect($batchData);

        $this->transformer = $transformer;

        $this->infType = $infType;

        $this->sourceCode = config('ifcert.platform_code');

        $this->setApiKey(Tools::getApiKey(config('ifcert.api_key'), $this->sourceCode, Client::version()));

        $this->dataType = config('ifcert.enter_db', 0);
    }

    /**
     * 数据整理
     */
    public function transform()
    {
        $transformer = $this->transformer;
        return $this->batchData->values()->map(function ($item) use ($transformer) {

            if ($transformer instanceof TransformerInterface) {
                return $transformer->format($item);
            }

            if (is_callable($transformer)) {
                return $transformer($item);
            }

            return $item;
        });
    }

    /**
     * @return false|string
     * @throws CertException
     */
    public function toJson()
    {
        return json_encode($this->getData()) ;
    }

    public function getData() {
        return [
            "version" => Client::version(),
            'batchNum' => $this->batchNumber ?? Tools::batchNumber($this->sourceCode),
            'checkCode' => Tools::checkCode($this->timestamp),
            'totalNum' => count($this->batchData),
            'sentTime' => $this->sendTime ? $this->sendTime->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
            'sourceCode' => $this->sourceCode,
            'infType' => $this->infType,
            'dataType' => $this->dataType,
            'timestamp' => $this->timestamp,
            'nonce' => $this->nonce,
            'dataList' => $this->transform()
        ];
    }


    public function setApiKey(array $apiKey)
    {
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

    public function setBatchNumber($tradeDate, $tradNum)
    {
        $this->batchNumber = Tools::batchNumber($this->sourceCode, $tradeDate, $tradNum);
        return $this;
    }

    /**
     * @return string
     * @throws CertException
     */
    protected function getUri()
    {
        switch ($this->infType) {
            case self::INF_TYPE_USER_INFO:
                return 'userInfo';
            case self::INF_TYPE_SCATTER_INVEST:
                return 'scatterInvest';
            case self::INF_TYPE_STATUS:
                return 'status';
            case self::INF_TYPE_REPAY_PLAN:
                return 'repayPlan';
            case self::INF_TYPE_CREDITOR:
                return 'creditor';
            case self::INF_TYPE_TRANSFER_PROJECT:
                return 'transferProject';
            case self::INF_TYPE_TRANSFER_STATUS:
                return 'transStatus';
            case self::INF_TYPE_UNDER_TAKE:
                return 'underTable';
            case self::INF_TYPE_TRANSACT:
                return 'transact';
            case self::INF_TYPE_LEND_PRODUCT:
                return 'lendProduct';
            case self::INF_TYPE_LEND_PRODUCT_CONFIG:
                return 'lendProductConfig';
            case self::INF_TYPE_LEND_PARTICULARS:
                return 'lendParticulars';
            default:
                throw new CertException('undefined inftype');
        }
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }


    protected function isProduction()
    {
        return (Client::env() === 'production' || Client::env() === 'prod');
    }

    /**
     * @return string
     * @throws Exceptions\CertException
     */
    public function getUrl()
    {
        $url = self::$url . '/' . $this->getUri();
        return $this->isProduction() ? $url : $url . '/test';
    }
}
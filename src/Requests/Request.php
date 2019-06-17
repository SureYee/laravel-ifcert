<?php

namespace Sureyee\LaravelIfcert\Requests;


use Carbon\Carbon;
use Illuminate\Support\Collection;
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
        self::INF_TYPE_USER_INFO => 'userinfo'
    ];

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
     * 版本号
     * @var string
     */
    protected $version;

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
     * @param \Closure|TransformerInterface $transformer
     */
    public function __construct($batchData, $infType, $transformer = null)
    {
        $this->batchData = $batchData instanceof Collection ? $batchData : collect($batchData);

        $this->transformer = $transformer;

        $this->infType = $infType;

        $this->sourceCode = config('ifcert.platform_code');
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
            "version" => $this->version,
            'batchNum' => $this->batchNumber ?? Tools::batchNumber($this->sourceCode),
            'checkCode' => Tools::checkCode($this->timestamp),
            'totalNum' => count($this->batchData),
            'sentTime' => $this->sendTime->format('Y-m-d H:i:s'),
            'sourceCode' => $this->sourceCode,
            'infType' => $this->infType,
            'dataType' => $this->dataType,
            'timestamp' => $this->timestamp,
            'nonce' => $this->nonce,
            'dataList' => $this->transform()
        ];
    }


    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
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

    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
        return $this;
    }

    public function setBatchNumber($tradeDate, $tradNum)
    {
        $this->batchNumber = Tools::batchNumber($this->sourceCode, $tradeDate, $tradNum);
        return $this;
    }

    public function getUri()
    {

    }
}
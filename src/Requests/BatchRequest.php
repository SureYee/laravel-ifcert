<?php

namespace Sureyee\LaravelIfcert\Requests;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Contracts\TransformerInterface;
use Sureyee\LaravelIfcert\Exceptions\CertException;
use Sureyee\LaravelIfcert\Tools;

class BatchRequest extends Request
{


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
     * BatchRequest constructor.
     * @param $batchData
     * @param $infType
     * @param null $transformer
     * @throws CertException
     */
    public function __construct($batchData, $infType, $transformer = null)
    {

        $this->batchData = $batchData instanceof Collection ? $batchData : collect($batchData);

        $this->transformer = $transformer;

        $this->sourceCode = config('ifcert.platform_code');

        $this->setApiKey(Tools::getApiKey(config('ifcert.api_key'), $this->sourceCode, Client::version()));

        $this->dataType = config('ifcert.enter_db', 0);

        $this->infType = $infType;

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

    /**
     * 获取数据
     * @return array
     * @throws CertException
     */
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

    /**
     * 获取uri
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
                return 'underTake';
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

    /**
     * 获取接口请求地址
     * @return string
     * @throws CertException
     */
    public function getUrl()
    {
        $url = self::$url . '/' . $this->getUri();
        return $this->isProduction() ? $url : $url . '/test';
    }

    public function getMethod()
    {
        return 'POST';
    }

    /**
     * @return string
     * @throws CertException
     */
    public function getBody()
    {
        return $this->encodeData([
            'apiKey' => $this->getApiKey(),
            'msg' => $this->toJson()
        ]);
    }

    public function getHeaders()
    {
        return ['content-type' => 'application/x-www-form-urlencoded;charset=UTF-8'];
    }

    protected function encodeData(array $data) {
        $o = '';
        foreach ($data as $key => $datum) {
            $o .= "{$key}=" . urlencode($datum) . "&";
        }
        return rtrim($o, '&');
    }
}
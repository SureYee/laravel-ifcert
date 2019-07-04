<?php

namespace Sureyee\LaravelIfcert\Requests;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Sureyee\LaravelIfcert\Client;
use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Contracts\TransformerInterface;
use Sureyee\LaravelIfcert\Exceptions\CertException;
use Sureyee\LaravelIfcert\Models\Creditor;
use Sureyee\LaravelIfcert\Models\Particular;
use Sureyee\LaravelIfcert\Models\Product;
use Sureyee\LaravelIfcert\Models\ProductConfig;
use Sureyee\LaravelIfcert\Models\RepayPlan;
use Sureyee\LaravelIfcert\Models\ScatterInvest;
use Sureyee\LaravelIfcert\Models\Status;
use Sureyee\LaravelIfcert\Models\Transact;
use Sureyee\LaravelIfcert\Models\TransferProject;
use Sureyee\LaravelIfcert\Models\TransferStatus;
use Sureyee\LaravelIfcert\Models\Undertake;
use Sureyee\LaravelIfcert\Models\UserInfo;
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
        parent::__construct($infType);

        if (!is_array(reset($batchData))) $batchData = [$batchData];

        $this->batchData = $batchData instanceof Collection ? $batchData : collect($batchData);

        $this->count = count($this->batchData);

        $this->transformer = $transformer;
    }

    /**
     * 数据整理
     */
    protected function transform()
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

    public function getDataList()
    {
        return $this->transform();
    }

    protected function buildBatchNumber()
    {
        $this->batchNumber = $this->batchNumber ?? Tools::batchNumber($this->sourceCode);
        return $this->batchNumber;
    }
    /**
     * 获取数据
     * @return array
     * @throws CertException
     */
    public function getData()
    {
        return [
            "version" => Client::version(),
            'batchNum' => $this->buildBatchNumber(),
            'checkCode' => Tools::checkCode($this->timestamp),
            'totalNum' => $this->count,
            'sentTime' => $this->sendTime->format('Y-m-d H:i:s'),
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
                return 'transferStatus';
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
     * @param $infType
     * @return  Model
     * @throws CertException
     */
    public function getModel()
    {
        switch ($this->infType) {
            case Request::INF_TYPE_LEND_PRODUCT_CONFIG:
                return app(ProductConfig::class);
            case Request::INF_TYPE_LEND_PRODUCT:
                return app(Product::class);
            case Request::INF_TYPE_USER_INFO:
                return app(UserInfo::class);
            case Request::INF_TYPE_TRANSACT:
                return app(Transact::class);
            case Request::INF_TYPE_TRANSFER_STATUS:
                return app(TransferStatus::class);
            case Request::INF_TYPE_REPAY_PLAN:
                return app(RepayPlan::class);
            case Request::INF_TYPE_UNDER_TAKE:
                return app(Undertake::class);
            case Request::INF_TYPE_TRANSFER_PROJECT:
                return app(TransferProject::class);
            case Request::INF_TYPE_CREDITOR:
                return app(Creditor::class);
            case  Request::INF_TYPE_SCATTER_INVEST:
                return app(ScatterInvest::class);
            case Request::INF_TYPE_LEND_PARTICULARS:
                return app(Particular::class);
            case Request::INF_TYPE_STATUS:
                return app(Status::class);
            default:
                throw new CertException('undefind inftype');
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
        return $this->isProduction() ? $url : ($url . '/test');
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

    /**
     * @return boolean
     * @throws CertException
     */
    public function store()
    {
        return $this->getModel()->insert($this->transform()->toArray());
    }

}
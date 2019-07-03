<?php

namespace Sureyee\LaravelIfcert;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Events\BeforeRequest;
use Sureyee\LaravelIfcert\Events\RequestFailed;
use Sureyee\LaravelIfcert\Events\RequestSuccess;
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
use Sureyee\LaravelIfcert\Requests\BatchRequest;
use Sureyee\LaravelIfcert\Responses\Response;
use GuzzleHttp\Psr7\Request as HttpRequest;
use GuzzleHttp\Exception\GuzzleException;

class Client
{

    private static $version = '1.5';

    private static $env = 'develop';

    /** @var \GuzzleHttp\Client  */
    protected $http;

    /** @var string */
    protected $platformCode;

    /** @var string */
    protected $apiKey;

    public function __construct()
    {
        $this->http = new \GuzzleHttp\Client();
        $this->apiKey = config('ifcert.api_key');
        $this->platformCode = config('ifcert.platform_code');
    }

    /**
     * 设置请求环境
     * @param $env
     */
    public static function setEnv($env)
    {
        self::$env = $env;
    }


    public static function version()
    {
        return self::$version;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws GuzzleException
     */
    public function send(Request $request)
    {
        event(new BeforeRequest($request));

        $httpRequest = $this->buildHttpRequest($request);

        $response = new Response($this->http->send($httpRequest));

        if ($response->isSuccess()) {
            event(new RequestSuccess($request, $response));
        } else {
            event(new RequestFailed($request, $response));
        }
        return $response;
    }

    /**
     * @param BatchRequest $request
     * @return mixed
     * @throws CertException
     */
    public function store(BatchRequest $request)
    {
        $model = $this->makeModel($request->getInfType());

        return $model->create($request->getDataList());
    }

    public static function env()
    {
        return self::$env;
    }

    protected function buildHttpRequest(Request $request)
    {
        return new HttpRequest($request->getMethod(), $request->getUrl(), $request->getHeaders(), $request->getBody());
    }

    /**
     * @param $infType
     * @return Model
     * @throws CertException
     */
    protected function makeModel($infType)
    {
        switch ($infType) {
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
                throw new CertException('undefind inf type');
        }
    }

}
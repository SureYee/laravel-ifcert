<?php

namespace Sureyee\LaravelIfcert;


use Carbon\Carbon;
use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Events\BeforeRequest;
use Sureyee\LaravelIfcert\Events\RequestFailed;
use Sureyee\LaravelIfcert\Events\RequestSuccess;
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



    public static function env()
    {
        return self::$env;
    }

    protected function buildHttpRequest(Request $request)
    {
        return new HttpRequest($request->getMethod(), $request->getUrl(), $request->getHeaders(), $request->getBody());
    }

}
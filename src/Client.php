<?php

namespace Sureyee\LaravelIfcert;


use Carbon\Carbon;
use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Responses\Response;
use GuzzleHttp\Psr7\Request as HttpRequest;

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
     * @param BatchRequest $request
     * @throws Exceptions\CertException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(Request $request)
    {


        $httpRequest = $this->buildHttpRequest($request);

        $response = $this->http->send($httpRequest);

        return new Response($response);
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
<?php

namespace Sureyee\LaravelIfcert;


use Carbon\Carbon;
use Sureyee\LaravelIfcert\Requests\Request;
use Sureyee\LaravelIfcert\Responses\Response;

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
     * @throws Exceptions\CertException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(Request $request)
    {
        $headers = [
          'content-type' => 'application/x-www-form-urlencoded;charset=UTF-8'
        ];

        $json = $request->toJson();

        $httpRequest = new \GuzzleHttp\Psr7\Request('POST', $request->getUrl(), $headers, $this->encodeData([
            'apiKey' => $request->getApiKey(),
            'msg' => $json
        ]));

        $response = $this->http->send($httpRequest);

        return new Response($response);
    }

    protected function encodeData(array $data) {
        $o = '';
        foreach ($data as $key => $datum) {
            $o .= "{$key}=" . urlencode($datum) . "&";
        }
        return rtrim($o, '&');
    }

    public static function env()
    {
        return self::$env;
    }

}
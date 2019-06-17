<?php

namespace Sureyee\LaravelIfcert;


use Carbon\Carbon;
use Sureyee\LaravelIfcert\Requests\Request;

class Client
{
    protected static $url = 'https://api.ifcert.org.cn/p2p';

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
        $apiKey = Tools::getApiKey($this->apiKey, $this->platformCode, self::$version);

        $headers = [
          'content-type' => 'application/x-www-form-urlencoded;charset=UTF-8'
        ];

        $json = $request->setVersion(self::$version)
            ->setApiKey($apiKey)
            ->setSendTime(Carbon::now())
            ->setDataType($this->getDataType())
            ->toJson();
        dd($json);
        $httpRequest = new \GuzzleHttp\Psr7\Request('POST', $this->getUri(), $headers, $this->encodeData([
            'apiKey' => $apiKey,
            'msg' => $json
        ]));

        $this->http->send($httpRequest);

    }

    protected function encodeData(array $data) {
        $o = '';
        foreach ($data as $key => $datum) {
            $o .= "{$key}=" . urlencode($datum) . "&";
        }
        return rtrim($o, '&');
    }

    protected function isProduction()
    {
        return (self::$env === 'production' || self::$env === 'prod');
    }

    protected function getDataType()
    {
        return  $this->isProduction() ? 1 : 0;
    }

    protected function getUri()
    {
        return $this->isProduction() ? self::$url : '';
    }
}
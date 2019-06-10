<?php

namespace SureYee\LaravelIfcert;


class Client
{

    private static $version = '1.5';

    /** @var \GuzzleHttp\Client  */
    protected $http;

    public function __construct()
    {
        $this->http = new \GuzzleHttp\Client();
    }


    public function getVersion()
    {
        return self::$version;
    }

    public function send()
    {

    }
}
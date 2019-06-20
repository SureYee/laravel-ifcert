<?php

namespace Sureyee\LaravelIfcert\Responses;


class Response
{
    private $code;

    private $message;

    private $result = [];


    public function __construct($response)
    {
        $json = json_decode((string) $response->getBody());

        $this->code = $json->code;
        $this->message = $json->message;
        $this->result = isset($json->result) ? $json->result : [];
    }

    public function isSuccess()
    {
        return $this->code === '0000';
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getCode()
    {
        return $this->code;
    }
}
<?php

namespace Sureyee\LaravelIfcert\Responses;


class Response
{
    private $code;

    private $message;


    public function __construct($response)
    {
        $json = json_decode((string) $response->getBody());

        $this->code = $json->code;
        $this->message = $json->message;
    }

    public function isSuccess()
    {

    }

    public function getMessage()
    {
        return $this->message;
    }
}
<?php

namespace Sureyee\LaravelIfcert\Responses;


class Response
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }
}
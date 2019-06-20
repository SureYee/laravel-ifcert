<?php

namespace Sureyee\LaravelIfcert\Events;


use Sureyee\LaravelIfcert\Contracts\Request;
use Sureyee\LaravelIfcert\Responses\Response;

class RequestSuccess
{
    public $request;

    public $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
<?php

namespace Sureyee\LaravelIfcert\Events;


use Sureyee\LaravelIfcert\Contracts\Request;

class BeforeRequest
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
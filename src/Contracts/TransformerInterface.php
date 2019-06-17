<?php

namespace Sureyee\LaravelIfcert\Contracts;

interface TransformerInterface
{
    public function format($item):array;
}
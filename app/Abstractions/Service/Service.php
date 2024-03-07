<?php

namespace App\Abstractions\Service;

abstract class Service
{
    public function execute( $class, $params )
    {
        return ( new $class( ...$params ) )->handle();
    }
}
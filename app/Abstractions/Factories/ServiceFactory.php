<?php

namespace App\Abstractions\Factories;

use App\Abstractions\Service\Service;

abstract class ServiceFactory
{
    /**
     * @param string $className
     * @param array $parameters
     * @return Service
     */
    protected static function resolve( string $className, array $parameters = [] ): Service
    {
        return resolve( $className, $parameters );
    }
}
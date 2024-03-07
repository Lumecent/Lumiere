<?php

namespace App\Abstractions\Factories;

use App\Abstractions\Interfaces\RepositoryInterface;

abstract class RepositoryFactory
{
    /**
     * @param string $className
     * @param array $parameters
     * @return RepositoryInterface
     */
    protected static function resolve( string $className, array $parameters = [] ): RepositoryInterface
    {
        return resolve( $className, $parameters );
    }
}
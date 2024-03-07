<?php

namespace App\Abstractions\Factories;

use App\Abstractions\Http\Dto\Dto;

abstract class DtoFactory
{
    /**
     * @param string $className
     * @param array $parameters
     * @return Dto
     */
    protected static function resolve( string $className, array $parameters ): Dto
    {
        return resolve( $className, $parameters );
    }
}
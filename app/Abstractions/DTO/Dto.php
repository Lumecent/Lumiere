<?php

namespace App\Abstractions\DTO;

use InvalidArgumentException;

abstract class Dto
{
    public function fromArray( array $params ): Dto
    {
        $dto_properties = get_class_vars( static::class );

        foreach ( $dto_properties as $dto_property => $value ) {
            if ( array_key_exists( $dto_property, $params ) ) {
                $this->$dto_property = $params[ $dto_property ];
            }
            else {
                throw new InvalidArgumentException( "$dto_property not found in params array" );
            }
        }
        return $this;
    }
}
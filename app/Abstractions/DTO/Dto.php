<?php

namespace App\Abstractions\DTO;

use App\Abstractions\Requests\Request;
use InvalidArgumentException;

abstract class Dto
{
    public function fromArray( array $params ): Dto
    {
        $dtoProperties = get_class_vars( static::class );

        foreach ( $dtoProperties as $dtoProperty => $value ) {
            if ( array_key_exists( $dtoProperty, $params ) ) {
                $this->$dtoProperty = $params[ $dtoProperty ];
            }
            else {
                throw new InvalidArgumentException( "$dtoProperty not found in params array" );
            }
        }
        return $this;
    }

    public function fromRequest( Request $request ): Dto
    {
        return $this->fromArray( $request->all() );
    }
}
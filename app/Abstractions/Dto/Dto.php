<?php

namespace App\Abstractions\Dto;

use App\Abstractions\Requests\FormRequest;
use App\Abstractions\Requests\Request;

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
                $this->$dtoProperty = null;
            }
        }
        return $this;
    }

    public function fromRequest( Request|FormRequest $request ): Dto
    {
        return $this->fromArray( $request->all() );
    }
}
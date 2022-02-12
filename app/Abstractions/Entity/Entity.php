<?php

namespace App\Abstractions\Entity;

use App\Abstractions\Models\Model;

abstract class Entity
{
    public function __construct( Model $model )
    {
        $properties = $model->toArray();

        $entityProperties = get_class_vars( static::class );

        foreach ( $entityProperties as $entityProperty => $value ) {
            if ( array_key_exists( $entityProperty, $properties ) ) {
                $this->$entityProperty = $properties[ $entityProperty ];
            }
        }
    }
}
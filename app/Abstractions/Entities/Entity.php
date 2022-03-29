<?php

namespace App\Abstractions\Entities;

use App\Abstractions\Models\Model;

abstract class Entity
{
    protected static array $hidden = [];

    public static function getEntity( ?Model $model ): ?Entity
    {
        $params = $model?->toArray();

        $properties = get_class_vars( static::class );

        $entity = app( static::class );

        if ( $params ) {
            foreach ( $properties as $property => $value ) {
                if ( array_key_exists( $property, $params ) ) {
                    $entity->$property = $params[ $property ];
                }
            }

            return $entity;
        }

        return null;
    }

    public function getVisible(): array
    {
        return array_diff_key( get_object_vars( $this ), array_flip( static::$hidden ) );
    }
}
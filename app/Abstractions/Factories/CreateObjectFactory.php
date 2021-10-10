<?php

namespace App\Abstractions\Factories;

use InvalidArgumentException;

abstract class CreateObjectFactory
{
    protected array $aliases = [];

    protected static ?self $instance = null;

    public static function create(): static
    {
        if ( is_null( self::$instance ) ) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->aliases = $this->getClassAliases();
    }

    public function __get( string $aliasClass )
    {
        if ( isset( $this->aliases[ $aliasClass ] ) ) {
            return new $this->aliases[ $aliasClass ];
        }
        throw new InvalidArgumentException( "Property $aliasClass not found in aliases table" );
    }

    public function __set( string $property, string $value ): void
    {
        $this->aliases[ $property ] = $value;
    }

    public function __isset( string $property ): bool
    {
        return isset( $this->aliases[ $property ] );
    }

    abstract public function getClassAliases(): array;
}
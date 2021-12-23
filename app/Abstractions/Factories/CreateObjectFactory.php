<?php

namespace App\Abstractions\Factories;

use InvalidArgumentException;

abstract class CreateObjectFactory
{
    protected array $aliases = [];

    protected static array $instances = [];

    public static function create(): static
    {
        $staticClass = static::class;
        if ( array_key_exists( $staticClass, self::$instances ) ) {
            return self::$instances[ $staticClass ];
        }
        self::$instances[ $staticClass ] = new static();

        return self::$instances[ $staticClass ];
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
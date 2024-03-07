<?php

namespace App\Abstractions\Providers;

use App\Abstractions\Http\Routes\ApiRoute;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

abstract class RouteServiceProvider extends IlluminateServiceProvider
{
    abstract public function routes(): void;

    public function boot(): void
    {
        $version = $this->getApiVersion();
        if ( $version ) {
            ApiRoute::prefix( "api/$version" )->name( "$version." )->group( function () {
                $this->routes();
            } );
        }
        else {
            $this->routes();
        }
    }

    private function getApiVersion(): ?string
    {
        $classRaw = explode( '\\', static::class );
        array_pop( $classRaw );

        $version = array_pop( $classRaw );
        $type = array_pop( $classRaw );

        return $type === 'Api' ? strtolower( $version ) : null;
    }
}

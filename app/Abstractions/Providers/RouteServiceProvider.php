<?php

namespace App\Abstractions\Providers;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

abstract class RouteServiceProvider extends IlluminateServiceProvider
{
    abstract public function routes(): void;

    public function boot(): void
    {
        $this->routes();
    }
}

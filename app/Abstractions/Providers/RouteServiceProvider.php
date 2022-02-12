<?php

namespace App\Abstractions\Providers;

abstract class RouteServiceProvider extends ServiceProvider
{
    abstract public function routes(): void;

    public function boot(): void
    {
        $this->routes();
    }
}

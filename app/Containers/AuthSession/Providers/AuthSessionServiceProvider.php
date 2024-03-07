<?php

namespace App\Containers\AuthSession\Providers;

use App\Abstractions\Providers\ServiceProvider;
use App\Containers\AuthSession\Interfaces\AuthSessionRepositoryInterface;
use App\Containers\AuthSession\Repositories\AuthSessionRepository;

class AuthSessionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind( AuthSessionRepositoryInterface::class, static fn() => new AuthSessionRepository() );
    }
}
<?php

namespace App\Containers\User\Providers;

use App\Abstractions\Providers\ServiceProvider;
use App\Containers\User\Interfaces\UserRepositoryInterface;
use App\Containers\User\Repositories\UserRepository;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind( UserRepositoryInterface::class, static fn() => new UserRepository() );
    }
}
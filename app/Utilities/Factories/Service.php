<?php

namespace App\Utilities\Factories;

use App\Abstractions\Factories\ServiceFactory;
use App\Containers\Auth\Services\AuthServiceFactory;
use App\Containers\User\Services\UserService;

class Service extends ServiceFactory
{
    public static function auth(): AuthServiceFactory
    {
        /** @var AuthServiceFactory $service */
        $service = self::resolve( AuthServiceFactory::class );

        return $service;
    }

    public static function user(): UserService
    {
        /** @var UserService $service */
        $service = self::resolve( UserService::class );

        return $service;
    }
}
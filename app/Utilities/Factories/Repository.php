<?php

namespace App\Utilities\Factories;

use App\Abstractions\Factories\RepositoryFactory;
use App\Containers\AuthSession\Interfaces\AuthSessionRepositoryInterface;
use App\Containers\User\Interfaces\UserRepositoryInterface;

class Repository extends RepositoryFactory
{
    public static function authSession(): AuthSessionRepositoryInterface
    {
        /** @var AuthSessionRepositoryInterface $interface */
        $interface = self::resolve( AuthSessionRepositoryInterface::class );

        return $interface;
    }

    public static function user(): UserRepositoryInterface
    {
        /** @var UserRepositoryInterface $interface */
        $interface = self::resolve( UserRepositoryInterface::class );

        return $interface;
    }
}
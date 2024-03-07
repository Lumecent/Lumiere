<?php

namespace App\Utilities\Factories;

use App\Abstractions\Factories\DtoFactory;
use App\Containers\Auth\Dto\AuthDto;
use App\Containers\AuthSession\Dto\AuthSessionDto;
use App\Containers\User\Dto\UserDto;

class Dto extends DtoFactory
{
    public static function auth( array $parameters ): AuthDto
    {
        /** @var AuthDto $dto */
        $dto = self::resolve( AuthDto::class, $parameters );

        return $dto;
    }

    public static function authSession( array $parameters ): AuthSessionDto
    {
        /** @var AuthSessionDto $dto */
        $dto = self::resolve( AuthSessionDto::class, $parameters );

        return $dto;
    }

    public static function user( array $parameters ): UserDto
    {
        /** @var UserDto $dto */
        $dto = self::resolve( UserDto::class, $parameters );

        return $dto;
    }
}
<?php

namespace App\Utilities\Facades;

use App\Abstractions\Http\Requests\Request;
use App\Containers\User\Models\User;
use App\Utilities\Factories\Dto;
use App\Utilities\Factories\Service;

class AuthUser
{
    private static ?User $user = null;
    private static Request $request;

    public function __construct( Request $request )
    {
        self::$request = $request;
    }

    public static function getId(): ?int
    {
        return self::getUser()?->id;
    }

    public static function getUser(): ?User
    {
        if ( self::$user === null ) {
            $token = self::$request->getHeader( 'Authorization' );

            $dto = Dto::auth( [
                'token' => str_replace( 'Bearer ', '', $token ),
                'user_agent' => self::$request->getUserAgent()
            ] );

            $user = Service::auth()->auth( $dto );
            if ( $user ) {
                self::$user = $user;
            }
        }
        return self::$user;
    }

    public static function isAuth(): bool
    {
        return (bool)self::getUser();
    }
}
<?php

namespace App\Containers\Auth\Services;

use App\Abstractions\Http\Dto\Dto;
use App\Abstractions\Service\Service;
use App\Containers\User\Models\User;

class AuthServiceFactory extends Service
{
    public function auth( Dto $dto ): ?User
    {
        return $this->execute( AuthorizationService::class, [ $dto ] );
    }

    public function login( Dto $dto, Dto $sessionDto ): ?User
    {
        return $this->execute( LoginService::class, [ $dto, $sessionDto ] );
    }
}

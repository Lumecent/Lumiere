<?php

namespace App\Containers\Auth\Services;

use App\Abstractions\Facades\Hash;
use App\Abstractions\Http\Dto\Dto;
use App\Abstractions\Interfaces\ServiceInterface;
use App\Containers\Auth\Dto\AuthDto;
use App\Containers\AuthSession\Dto\AuthSessionDto;
use App\Containers\User\Models\User;
use App\Utilities\Factories\Repository;

readonly class LoginService implements ServiceInterface
{
    /**
     * @param AuthDto $dto
     * @param AuthSessionDto $sessionDto
     */
    public function __construct( private Dto $dto, private Dto $sessionDto )
    {
    }

    /**
     * Реализует логику входа в систему
     *
     * @return User|null
     */
    public function handle(): ?User
    {
        /** @var User $user */
        $user = Repository::user()->findByEmail( $this->dto->email );
        if ( !$user || !Hash::check( $this->dto->password, $user->password ) ) {
            return null;
        }

        $session = Repository::authSession()->create( $this->sessionDto );

        return Repository::user()->saveAuthSession( $user, $session );
    }
}
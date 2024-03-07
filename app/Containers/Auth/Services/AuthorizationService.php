<?php

namespace App\Containers\Auth\Services;

use App\Abstractions\Http\Dto\Dto;
use App\Abstractions\Interfaces\ServiceInterface;
use App\Containers\Auth\Dto\AuthDto;
use App\Containers\AuthSession\Models\AuthSession;
use App\Containers\User\Models\User;
use App\Utilities\Factories\Repository;

readonly class AuthorizationService implements ServiceInterface
{
    /**
     * @param AuthDto $dto
     */
    public function __construct( private Dto $dto )
    {
    }

    public function handle(): ?User
    {
        if ( $this->dto->token ) {
            /** @var AuthSession $session */
            $session = Repository::authSession()->findByTokenAndUserAgent( $this->dto->token, $this->dto->user_agent );

            if ( $session && $session->model_type === User::class ) {
                /** @var User $user */
                $user = Repository::user()->findById( $session->model_id );

                return $user;
            }
        }

        return null;
    }
}

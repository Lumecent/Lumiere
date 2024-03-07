<?php

namespace App\Containers\User\Interfaces;

use App\Abstractions\Http\Dto\Dto;
use App\Abstractions\Interfaces\RepositoryInterface;
use App\Containers\AuthSession\Models\AuthSession;
use App\Containers\User\Models\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail( string $email ): ?User;

    public function saveAuthSession( User $user, AuthSession $session ): User;

    public function create( Dto $dto ): User;

    public function update( User $user, Dto $dto ): User;

    public function changePassword( User $user, Dto $dto ): User;

    public function delete( User $user ): bool;
}
<?php

namespace App\Containers\User\Repositories;

use App\Abstractions\Database\Repositories\Repository;
use App\Abstractions\Facades\Hash;
use App\Abstractions\Http\Dto\Dto;
use App\Containers\Auth\Dto\AuthDto;
use App\Containers\AuthSession\Models\AuthSession;
use App\Containers\User\Dto\UserDto;
use App\Containers\User\Interfaces\UserRepositoryInterface;
use App\Containers\User\Models\User;
use App\Utilities\Helpers\StringHelper;

class UserRepository extends Repository implements UserRepositoryInterface
{
    protected function getModelClass(): User
    {
        return new User();
    }

    public function findByEmail( string $email, array $columns = [ '*' ] ): ?User
    {
        /** @var User|null $user */
        $user = $this->query()->where( 'email', $email )->select( $columns )->first();

        return $user;
    }

    /**
     * @param UserDto $dto
     * @param User $user
     * @return User
     */
    public function changePassword( User $user, Dto $dto ): User
    {
        $user->password = Hash::make( $dto->password );

        $user->save();

        return $user;
    }


    public function saveAuthSession( User $user, AuthSession $session ): User
    {
        $user->session()->save( $session );

        return $user;
    }

    /**
     * @param AuthDto $dto
     * @return User
     */
    public function create( Dto $dto ): User
    {
        $user = $this->getModelClass();

        $user->fill( $dto->toArray() );

        $user->name = StringHelper::removeSpaces( $dto->name );
        $user->password = Hash::make( $dto->password );

        $user->save();

        $user->refresh();

        return $user;
    }

    /**
     * @param UserDto $dto
     * @param User $user
     * @return User
     */
    public function update( User $user, Dto $dto ): User
    {
        $user->name = StringHelper::removeSpaces( $dto->name );
        $user->email = $dto->email;

        $user->save();

        return $user;
    }

    public function delete( User $user ): bool
    {
        return $user->delete();
    }
}

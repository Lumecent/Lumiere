<?php

namespace Containers\User\Repositories;

use App\Abstractions\Facades\Hash;
use App\Containers\AuthSession\Models\AuthSession;
use App\Containers\User\Models\User;
use App\Utilities\Factories\Dto;
use App\Utilities\Factories\Repository;
use Tests\BaseTest;

class UserRepositoryTest extends BaseTest
{
    public function testFindByEmail(): void
    {
        $email = $this->faker->email();

        $actualUser = User::factory()->create( [
            'email' => $email
        ] );

        $expectedUser = Repository::user()->findByEmail( $email );

        $this->assertSame( $expectedUser->email, $actualUser->email );
    }

    public function testChangePassword(): void
    {
        $string = $this->faker->password;

        $user = User::factory()->create();
        $dto = Dto::user( [ 'password' => $string ] );

        $user = Repository::user()->changePassword( $user, $dto );

        $this->assertTrue( Hash::check( $string, $user->password ) );
    }

    public function testSaveAuthSession(): void
    {
        $session = AuthSession::factory()->create();
        $user = User::factory()->create();

        Repository::user()->saveAuthSession( $user, $session );

        $this->assertSame($user->session->toArray(), $session->refresh()->toArray());
    }

    public function testCreate(): void
    {
        $params = User::factory()->definition();

        $dto = Dto::user( $params );

        $user = Repository::user()->create( $dto );

        $this->assertTrue( $user->exists );
    }

    public function testUpdate(): void
    {
        $actualUser = User::factory()->create();

        $params = [
            'name' => $this->faker->name,
            'email' => $this->faker->email
        ];

        $dto = Dto::user( $params );

        $expectedUser = Repository::user()->update( $actualUser, $dto );

        $this->assertSame( $expectedUser->name, $actualUser->name );
        $this->assertSame( $expectedUser->email, $actualUser->email );
    }

    public function testDelete(): void
    {
        $user = User::factory()->create();

        Repository::user()->delete( $user );

        $user->refresh();

        $this->assertFalse( $user->exists );
    }
}
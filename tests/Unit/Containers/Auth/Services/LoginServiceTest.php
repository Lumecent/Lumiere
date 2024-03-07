<?php

namespace Containers\Auth\Services;

use App\Abstractions\Facades\Hash;
use App\Containers\AuthSession\Models\AuthSession;
use App\Containers\User\Models\User;
use App\Utilities\Factories\Dto;
use App\Utilities\Factories\Repository;
use App\Utilities\Factories\Service;
use Tests\BaseTest;

class LoginServiceTest extends BaseTest
{
    public function testLogin(): void
    {
        $password = $this->faker->password;

        $user = User::factory()->create( [ 'password' => Hash::make( $password ) ] );
        $session = AuthSession::factory()->create();

        Repository::user()->saveAuthSession( $user, $session );

        $loginDto = Dto::auth( [ 'email' => $user->email, 'password' => $password ] );
        $sessionDto = Dto::authSession( [
            'user_agent' => $session->user_agent,
            'ip' => $session->id
        ] );

        $user = Service::auth()->login( $loginDto, $sessionDto );

        $this->assertNotNull( $user );
    }
}
<?php

namespace Containers\Auth\Services;

use App\Containers\AuthSession\Models\AuthSession;
use App\Containers\User\Models\User;
use App\Utilities\Factories\Dto;
use App\Utilities\Factories\Repository;
use App\Utilities\Factories\Service;
use Tests\BaseTest;

class AuthorizationServiceTest extends BaseTest
{
    public function testAuth(): void
    {
        $user = User::factory()->create();
        $session = AuthSession::factory()->create();

        Repository::user()->saveAuthSession( $user, $session );

        $authDto = Dto::auth( [ 'token' => $session->token, 'user_agent' => $session->user_agent ] );

        $user = Service::auth()->auth( $authDto );

        $this->assertNotNull( $user );
    }
}
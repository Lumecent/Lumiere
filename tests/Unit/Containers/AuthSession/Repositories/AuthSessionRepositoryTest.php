<?php

namespace Containers\AuthSession\Repositories;

use App\Containers\AuthSession\Models\AuthSession;
use App\Containers\User\Models\User;
use App\Utilities\Factories\Dto;
use App\Utilities\Factories\Repository;
use Tests\BaseTest;

class AuthSessionRepositoryTest extends BaseTest
{
    public function testFindByTokenAndUserAgent(): void
    {
        $session = AuthSession::factory()->create();

        $findSession = Repository::authSession()->findByTokenAndUserAgent( $session->token, $session->user_agent );

        $this->assertSame( $findSession->id, $findSession->id );

        $newSession = AuthSession::factory()->create();

        $findSession = Repository::authSession()->findByTokenAndUserAgent( $newSession->token, $newSession->user_agent );

        $this->assertNotSame( $findSession->id, $session->id );
    }

    public function testCreate(): void
    {
        $params = AuthSession::factory()->definition();

        $dto = Dto::authSession( $params );

        $session = Repository::authSession()->create( $dto );

        $this->assertTrue( $session->exists );
    }

    public function testDelete(): void
    {
        $session = AuthSession::factory()->create();
        $user = User::factory()->create();

        Repository::user()->saveAuthSession( $user, $session );

        $session->refresh();

        Repository::authSession()->delete( $session->model_id, $session->token );

        $this->assertNull( Repository::authSession()->findByTokenAndUserAgent( $session->token, $session->user_agent ) );
    }
}
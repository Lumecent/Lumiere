<?php

namespace App\Containers\AuthSession\Repositories;

use App\Abstractions\Database\Repositories\Repository;
use App\Abstractions\Facades\Hash;
use App\Abstractions\Http\Dto\Dto;
use App\Containers\AuthSession\Dto\AuthSessionDto;
use App\Containers\AuthSession\Interfaces\AuthSessionRepositoryInterface;
use App\Containers\AuthSession\Models\AuthSession;
use Carbon\Carbon;

class AuthSessionRepository extends Repository implements AuthSessionRepositoryInterface
{
    protected function getModelClass(): AuthSession
    {
        return new AuthSession();
    }

    public function findByTokenAndUserAgent( string $token, ?string $userAgent ): ?AuthSession
    {
        /** @var AuthSession|null $session */
        $session = $this->query()
            ->where( 'token', $token )
            ->where( 'user_agent', $userAgent )
            ->first();

        return $session;
    }

    /**
     * @param AuthSessionDto $dto
     * @return AuthSession
     */
    public function create( Dto $dto ): AuthSession
    {
        $session = $this->getModelClass();

        $session->user_agent = $dto->user_agent;
        $session->ip = $dto->ip;
        $session->token = Hash::make( $dto->user_agent ?? '' . Carbon::now()->timestamp );
        $session->expired_at = Carbon::now()->addMonth();

        $session->save();

        return $session;
    }

    public function delete( int $modelId, ?string $authToken ): bool
    {
        return $this->query()
            ->where( 'model_id', $modelId )
            ->where( 'token', $authToken )
            ?->delete();
    }
}

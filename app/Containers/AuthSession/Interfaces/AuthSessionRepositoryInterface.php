<?php

namespace App\Containers\AuthSession\Interfaces;

use App\Abstractions\Http\Dto\Dto;
use App\Abstractions\Interfaces\RepositoryInterface;
use App\Containers\AuthSession\Models\AuthSession;

interface AuthSessionRepositoryInterface extends RepositoryInterface
{
    public function findByTokenAndUserAgent( string $token, ?string $userAgent ): ?AuthSession;

    public function create( Dto $dto ): AuthSession;

    public function delete( int $modelId, ?string $authToken ): bool;
}
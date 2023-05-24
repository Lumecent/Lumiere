<?php

namespace App\Abstractions\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function query(): Builder;

    public function findById( int $id, array $columns = [ '*' ] ): ?Model;

    public function findByIdOrFail( int $id, array $columns = [ '*' ] ): ?Model;

    public function findByIdOrError( int $id, array $columns = [ '*' ] ): ?Model;

    public function findByIdWithRelations( int $id, string|array $relation, array $columns = [ '*' ] ): ?Model;

    public function findByUserId( int $userId, array $columns = [ '*' ] ): ?Model;
}
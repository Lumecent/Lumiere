<?php

namespace App\Abstractions\Repositories;

use App\Abstractions\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryInterface
{
    abstract protected function getModelClass(): Model;

    public function query(): Builder
    {
        return $this->getModelClass()::query();
    }

    public function findById( int $id, array $columns = [ '*' ] ): ?Model
    {
        return $this->getModelClass()::query()->find( $id, $columns );
    }

    public function findByIdOrFail( int $id, array $columns = [ '*' ] ): ?Model
    {
        return $this->getModelClass()::query()->findOrFail( $id, $columns );
    }

    public function findByIdWithRelations( int $id, string|array $relation, array $columns = [ '*' ] ): ?Model
    {
        return $this->getModelClass()::query()->with( $relation )->find( $id, $columns );
    }

    public function findByUserId( int $userId, array $columns = [ '*' ] ): ?Model
    {
        return $this->getModelClass()::query()->select( $columns )->firstWhere( 'user_id', $userId );
    }
}

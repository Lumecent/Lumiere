<?php

namespace App\Abstractions\Database\Repositories;

use App\Abstractions\Collections\ModelsCollection;
use App\Abstractions\Database\Builders\Builder;
use App\Abstractions\Database\Models\Model;
use App\Abstractions\Interfaces\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    abstract protected function getModelClass(): Model;

    public function query(): Builder
    {
        return $this->getModelClass()::query();
    }

    public function findById( int $id, array $relations = [], array $columns = [ '*' ] ): ?Model
    {
        return $this->getModelClass()::query()
            ->with( $relations )
            ->find( $id, $columns );
    }

    public function findByIdWithTrashed( int $id, array $relations = [], array $columns = [ '*' ] ): ?Model
    {
        return $this->getModelClass()::withTrashed()
            ->with( $relations )
            ->find( $id );
    }

    public function findByUserId( int $userId, array $relations = [], array $columns = [ '*' ] ): ?Model
    {
        return $this->getModelClass()::query()
            ->where( 'user_id', $userId )
            ->with( $relations )
            ->select( $columns )
            ->first();
    }

    public function getByUserId( int $userId, array $relations = [], array $columns = [ '*' ] ): ModelsCollection
    {
        return $this->query()
            ->where( 'user_id', $userId )
            ->with( $relations )
            ->select( $columns )
            ->get();
    }
}

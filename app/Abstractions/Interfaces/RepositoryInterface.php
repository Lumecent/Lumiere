<?php

namespace App\Abstractions\Interfaces;


use App\Abstractions\Collections\ModelsCollection;
use App\Abstractions\Database\Builders\Builder;
use App\Abstractions\Database\Models\Model;

interface RepositoryInterface
{
    public function query(): Builder;

    public function findById( int $id, array $relations = [], array $columns = [ '*' ] ): ?Model;

    public function findByIdWithTrashed( int $id, array $relations = [], array $columns = [ '*' ] ): ?Model;

    public function findByUserId( int $userId, array $relations = [], array $columns = [ '*' ] ): ?Model;

    public function getByUserId( int $userId, array $relations = [], array $columns = [ '*' ] ): ModelsCollection;
}
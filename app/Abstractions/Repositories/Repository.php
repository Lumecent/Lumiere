<?php

namespace App\Abstractions\Repositories;

use App\Abstractions\DTO\Dto;
use App\Abstractions\Entities\Entity;
use App\Abstractions\Models\Model;

abstract class Repository
{
    abstract protected static function getModelClass(): string;

    abstract protected function getEntity( ?Model $model ): ?Entity;

    abstract public function create( Dto $dto ): ?Entity;

    abstract public function update( Dto $dto ): ?Entity;

    abstract public function delete( Dto $dto ): bool;

    public function startConditions(): Model
    {
        $model = static::getModelClass();
        return new $model();
    }
}

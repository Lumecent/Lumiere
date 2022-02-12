<?php

namespace App\Abstractions\Repositories;

use App\Abstractions\Entity\Entity;
use App\Abstractions\Models\Model;

abstract class Repository
{
    public function startConditions(): Model
    {
        $model = static::getModelClass();
        return new $model();
    }

    abstract protected static function getModelClass(): string;

    abstract protected static function getEntity( Model $model ): Entity;
}

<?php

namespace App\Abstractions\Repositories;

use App\Abstractions\Models\Model;

abstract class Repository
{
    private static ?Model $model = null;

    public static function startConditions(): Model
    {
        if ( is_null( self::$model ) ) {
            $model = static::getModelClass();
            self::$model = new $model();
        }
        return self::$model;
    }

    abstract protected static function getModelClass(): string;
}

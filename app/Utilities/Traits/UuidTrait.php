<?php

namespace App\Utilities\Traits;

use Illuminate\Support\Str;

/**
 * Необходим для моделей, которые используют GUID в виде ID записей в таблице
 */
trait UuidTrait
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating( function ( $item ) {
            $item->{$item->getKeyName()} = (string)Str::uuid();
        } );
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }
}
<?php

namespace App\Abstractions\Http\Dto;

abstract readonly class Dto
{
    public function toArray(): array
    {
        return get_object_vars( $this );
    }
}
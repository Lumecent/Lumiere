<?php

namespace App\Containers\AuthSession\Dto;

use App\Abstractions\Http\Dto\Dto;

readonly class AuthSessionDto extends Dto
{
    public function __construct(
        public ?string $user_agent = null,
        public ?string $ip = null
    )
    {
    }
}

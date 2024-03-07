<?php

namespace App\Containers\Auth\Dto;

use App\Abstractions\Http\Dto\Dto;

readonly class AuthDto extends Dto
{
    public function __construct(
        public ?string $token = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?string $user_agent = null
    )
    {
    }
}

<?php

namespace App\Containers\User\Dto;

use App\Abstractions\Http\Dto\Dto;

readonly class UserDto extends Dto
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
    )
    {
    }
}

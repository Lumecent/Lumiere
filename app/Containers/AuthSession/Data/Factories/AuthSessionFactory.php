<?php

namespace App\Containers\AuthSession\Data\Factories;

use App\Abstractions\Facades\Hash;
use App\Abstractions\Factories\EloquentFactory;
use App\Containers\AuthSession\Models\AuthSession;
use Illuminate\Support\Carbon;

class AuthSessionFactory extends EloquentFactory
{
    protected $model = AuthSession::class;

    public function definition(): array
    {
        return [
            'user_agent' => $this->faker->userAgent,
            'ip' => $this->faker->ipv4,
            'token' => Hash::make( $this->faker->password ),
            'expired_at' => Carbon::now()->addMonth()
        ];
    }
}

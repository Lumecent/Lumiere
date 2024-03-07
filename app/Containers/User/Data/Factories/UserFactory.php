<?php

namespace App\Containers\User\Data\Factories;

use App\Abstractions\Facades\Hash;
use App\Abstractions\Factories\EloquentFactory;
use App\Containers\User\Models\User;

class UserFactory extends EloquentFactory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name,
            'email' => $this->faker->unique()->email,
            'password' => Hash::make( 'secret' )
        ];
    }
}

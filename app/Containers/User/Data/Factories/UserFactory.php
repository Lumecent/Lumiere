<?php

namespace App\Containers\User\Data\Factories;

use App\Abstractions\Factories\EloquentFactory;
use App\Containers\User\Models\User;

class UserFactory extends EloquentFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return EloquentFactory
     */
    public function unverified(): EloquentFactory
    {
        return $this->state(function (array $attributes) {
            return [

            ];
        });
    }
}

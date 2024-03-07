<?php

namespace Tests\Unit\Utilities\Factories\Dto;

use App\Utilities\Factories\Dto;
use Tests\BaseTest;

class InitTest extends BaseTest
{
    public function testInit()
    {
        $parameters = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
        ];

        $dto = Dto::user( $parameters );

        $this->assertEquals( $dto->toArray(), $parameters );
    }
}

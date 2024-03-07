<?php

namespace App\Containers\User\Data\Seeders;

use App\Abstractions\Database\Seeders\Seeder;
use App\Containers\User\Models\User;
use App\Utilities\Factories\Repository;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory( rand( 5, 50 ) )->create();
    }

    public function truncate(): void
    {
        $this->disableForeignKeys();

        Repository::user()->query()->truncate();

        $this->enableForeignKeys();
    }
}

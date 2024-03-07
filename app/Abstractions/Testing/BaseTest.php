<?php

namespace App\Abstractions\Testing;

use App\Abstractions\Facades\Storage;
use App\Containers\AuthSession\Models\AuthSession;
use App\Utilities\Traits\FactoryCreateModelsTrait;
use App\Utilities\Traits\FactoryParamsModelsTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BaseTest extends TestCase
{
    use WithFaker, RefreshDatabase, FactoryCreateModelsTrait, FactoryParamsModelsTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();

        Storage::fake( 'images' );
        Storage::fake( 'images_original' );
    }

    public function buildAuthUserHeader( AuthSession $session ): array
    {
        return [
            'Authorization' => "Bearer $session->token"
        ];
    }
}
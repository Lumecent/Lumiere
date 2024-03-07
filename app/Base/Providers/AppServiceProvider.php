<?php

namespace App\Base\Providers;

use App\Utilities\Facades\AuthUser;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        resolve( AuthUser::class );
    }
}

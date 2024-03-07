<?php

namespace App\Abstractions\Providers;

use App\Utilities\Accessors\AuthModeratorAccessor;
use App\Utilities\Accessors\AuthUserAccessor;
use App\Utilities\Accessors\DtoAccessor;
use App\Utilities\Accessors\FilterAccessor;
use App\Utilities\Accessors\RepositoryAccessor;
use App\Utilities\Accessors\ServiceAccessor;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class FacadesServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        App::bind( 'AuthUser', function () {
            return resolve( AuthUserAccessor::class );
        } );

        App::bind( 'AuthModerator', function () {
            return resolve( AuthModeratorAccessor::class );
        } );

        App::bind( 'Dto', function () {
            return resolve( DtoAccessor::class );
        } );

        App::bind( 'Filter', function () {
            return resolve( FilterAccessor::class );
        } );

        App::bind( 'Repository', function () {
            return resolve( RepositoryAccessor::class );
        } );

        App::bind( 'Service', function () {
            return resolve( ServiceAccessor::class );
        } );

    }
}
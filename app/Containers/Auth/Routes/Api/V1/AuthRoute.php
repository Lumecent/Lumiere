<?php

namespace App\Containers\Auth\Routes\Api\V1;

use App\Abstractions\Http\Routes\ApiRoute;
use App\Abstractions\Providers\RouteServiceProvider;
use App\Containers\Auth\Controllers\Api\AuthController;

class AuthRoute extends RouteServiceProvider
{
    public function routes(): void
    {
        ApiRoute::prefix( '' )->group( static function () {
            ApiRoute::get( '/auth', [ AuthController::class, 'auth' ] );

            ApiRoute::post( '/register', [ AuthController::class, 'register' ] );
            ApiRoute::post( '/login', [ AuthController::class, 'login' ] );

            ApiRoute::group( [ 'middleware' => 'api.auth' ], static function () {
                ApiRoute::get( '/logout', [ AuthController::class, 'logout' ] );
            } );
        } );
    }
}

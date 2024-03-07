<?php

namespace App\Base\Middleware;

use App\Abstractions\Http\Responses\ApiResponse;
use App\Utilities\Facades\AuthModerator;
use Closure;
use Illuminate\Http\Request;

class AuthModeratorMiddleware
{
    public function handle( Request $request, Closure $next ): mixed
    {
        if ( AuthModerator::isAuth() ) {
            return $next( $request );
        }
        return ApiResponse::sendUnAuthorised();
    }
}

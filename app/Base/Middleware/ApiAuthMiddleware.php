<?php

namespace App\Base\Middleware;

use App\Abstractions\Http\Responses\ApiResponse;
use App\Utilities\Facades\AuthUser;
use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    public function handle( Request $request, Closure $next ): mixed
    {
        if ( AuthUser::isAuth() ) {
            return $next( $request );
        }
        return ApiResponse::sendUnAuthorised();
    }
}

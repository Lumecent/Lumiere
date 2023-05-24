<?php

namespace App\Base\Middleware;

use App\Abstractions\Responses\ApiResponse;
use App\Utilities\Facades\AuthUser;
use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle( Request $request, Closure $next ): mixed
    {
        if ( AuthUser::isAuth() ) {
            return $next( $request );
        }
        return ApiResponse::sendUnAuthorised();
    }
}

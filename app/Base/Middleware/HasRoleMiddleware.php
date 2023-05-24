<?php

namespace App\Base\Middleware;

use App\Abstractions\Responses\ApiResponse;
use App\Utilities\Facades\AuthUser;
use Closure;
use Illuminate\Http\Request;

class HasRoleMiddleware
{
    public function handle( Request $request, Closure $next, ...$roles ): mixed
    {
        foreach ( $roles as $role ) {
            if ( AuthUser::getUser()->roles()->firstWhere( 'name', $role ) ) {
                return $next( $request );
            }
        }

        return ApiResponse::sendPermissionDenied();
    }
}

<?php

namespace App\Base\Middleware;

use App\Abstractions\Http\Responses\ApiResponse;
use App\Utilities\Facades\AuthModerator;
use Closure;
use Illuminate\Http\Request;

class HasRoleMiddleware
{
    public function handle( Request $request, Closure $next, ...$roles ): mixed
    {
        foreach ( $roles as $role ) {
            if ( AuthModerator::getModerator()->roles()->firstWhere( 'name', $role ) ) {
                return $next( $request );
            }
        }

        return ApiResponse::sendPermissionDenied();
    }
}

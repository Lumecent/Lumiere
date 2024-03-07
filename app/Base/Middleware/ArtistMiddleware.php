<?php

namespace App\Base\Middleware;

use App\Abstractions\Http\Responses\ApiResponse;
use App\Utilities\Facades\AuthUser;
use Closure;
use Illuminate\Http\Request;

class ArtistMiddleware
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
        if ( AuthUser::isAuth() && AuthUser::getUser()->agree_offer && AuthUser::getArtist() ) {
            return $next( $request );
        }
        return ApiResponse::sendUnAuthorised();
    }
}

<?php

namespace App\Abstractions\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    public static function sendData( string $flashMessage = '', array $data = [], int $status = 200 ): ApiResponse
    {
        $response = [ 'message' => $flashMessage ];

        if ( $status && $status < 400 ) {
            $response = array_merge( $response, $data );
        }
        else {
            $response = array_merge( $response, [ 'errors' => $data ] );
        }

        return new ApiResponse( $response, $status );
    }
}

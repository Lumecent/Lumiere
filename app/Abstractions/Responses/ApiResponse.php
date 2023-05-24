<?php

namespace App\Abstractions\Responses;

use Illuminate\Http\JsonResponse;
use Throwable;

class ApiResponse extends JsonResponse
{
    public static function success( array $data = [] ): ApiResponse
    {
        return static::sendData( '', $data );
    }

    public static function successWithMessage( string $message = '', array $data = [] ): ApiResponse
    {
        return static::sendData( $message, $data );
    }

    public static function exception( Throwable $exception ): ApiResponse
    {
        return static::sendData( $exception->getMessage(), [], 500 );
    }

    public static function error( string $message = '', array $data = [], int $status = 500 ): ApiResponse
    {
        return static::sendData( $message, $data, $status );
    }

    public static function sendUnAuthorised( string $flashMessage = '', int $status = 401 ): ApiResponse
    {
        $response = [
            'message' => $flashMessage ?: 'Для получения доступа необходимо авторизоваться',
        ];

        return new ApiResponse( $response, $status );
    }

    public static function sendPermissionDenied( string $flashMessage = '', int $status = 403 ): ApiResponse
    {
        $response = [
            'message' => $flashMessage ?: 'Доступ запрещён',
        ];

        return new ApiResponse( $response, $status );
    }

    public static function notFound( string $flashMessage = '' ): ApiResponse
    {
        $response = [
            'message' => $flashMessage ?: 'Указанная сущность не найдена',
        ];

        return new ApiResponse( $response, 404 );
    }

    private static function sendData( string $flashMessage = '', array $data = [], int $status = 200 ): ApiResponse
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

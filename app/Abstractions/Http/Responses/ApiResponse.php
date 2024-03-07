<?php

namespace App\Abstractions\Http\Responses;

use Illuminate\Http\JsonResponse;
use Throwable;

class ApiResponse extends JsonResponse
{
    public static function success( array $data = [], string $message = '' ): ApiResponse
    {
        return static::sendData( $message, $data );
    }

    public static function error( string $message = '', int $status = 500 ): ApiResponse
    {
        return static::sendData( $message, [], $status );
    }

    public static function exception( Throwable $exception ): ApiResponse
    {
        return static::sendData( $exception->getMessage(), [], 500 );
    }

    public static function sendValidateErrors( array $data = [], string $message = '', int $status = 422 ): ApiResponse
    {
        return static::sendData( $message, $data, $status );
    }

    public static function sendUnAuthorised(): ApiResponse
    {
        $response = [
            'message' => 'Для получения доступа необходимо авторизоваться',
        ];

        return new ApiResponse( $response, 401 );
    }

    public static function sendPermissionDenied(): ApiResponse
    {
        $response = [
            'message' => 'Доступ запрещён',
        ];

        return new ApiResponse( $response, 403 );
    }

    public static function notFound(): ApiResponse
    {
        $response = [
            'message' => 'Указанная сущность не найдена',
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

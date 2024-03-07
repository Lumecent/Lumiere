<?php

namespace App\Containers\Auth\Controllers\Api;

use App\Abstractions\Facades\DB;
use App\Abstractions\Http\Controllers\ApiController;
use App\Abstractions\Http\Requests\Request;
use App\Abstractions\Http\Responses\ApiResponse;
use App\Containers\Auth\Exceptions\LoginException;
use App\Containers\Auth\Exceptions\RegisterException;
use App\Containers\Auth\Requests\LoginRequest;
use App\Containers\Auth\Requests\RegisterRequest;
use App\Containers\User\Models\User;
use App\Containers\User\Resources\UserResource;
use App\Utilities\Facades\AuthUser;
use App\Utilities\Factories\Dto;
use App\Utilities\Factories\Repository;
use App\Utilities\Factories\Service;
use Throwable;

class AuthController extends ApiController
{
    /**
     * @throws Throwable
     */
    public function register( RegisterRequest $request ): ApiResponse
    {
        DB::beginTransaction();

        try {
            $user = Repository::user()->create(
                Dto::auth( $request->validated() )
            );

            $session = Repository::authSession()->create(
                Dto::authSession( [
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip()
                ] )
            );

            Repository::user()->saveAuthSession( $user, $session );

        } catch ( Throwable $exception ) {
            DB::rollBack();

            return throw new RegisterException( $exception );
        }

        DB::commit();

        return ApiResponse::success(
            [ 'user' => UserResource::make( $user ), 'token' => $session->token ],
            'Регистрация прошла успешно'
        );
    }

    public function login( LoginRequest $request ): ApiResponse
    {
        DB::beginTransaction();

        try {
            $loginDto = Dto::auth( $request->validated() );
            $sessionDto = Dto::authSession( [
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ] );

            /** @var User $user */
            $user = Service::auth()->login( $loginDto, $sessionDto );
            if ( $user ) {
                DB::commit();

                return ApiResponse::success(
                    [ 'user' => UserResource::make( $user ), 'token' => $user->session->token ],
                    'Авторизация прошла успешно'
                );
            }
        } catch ( Throwable $exception ) {
            DB::rollBack();

            new LoginException( $exception );
        }

        return ApiResponse::sendValidateErrors(
            [ 'email' => [ 'Введен неверный e-mail адрес или пароль' ] ],
            'Введены некорректные данные',
        );
    }

    public function auth( Request $request ): ApiResponse
    {
        $dto = Dto::auth( [ 'token' => $request->getAuthToken(), 'user_agent' => $request->getUserAgent() ] );

        $user = Service::auth()->auth( $dto );

        if ( $user ) {
            return ApiResponse::success( [ 'user' => UserResource::make( $user ), 'token' => $request->getAuthToken() ] );
        }

        return ApiResponse::success( [ 'user' => null, 'token' => null ] );
    }

    public function logout( Request $request ): ApiResponse
    {
        if ( Repository::authSession()->delete( AuthUser::getId(), $request->getAuthToken() ) ) {
            return ApiResponse::success( [ 'user' => null, 'token' => null ], 'Выход выполнен успешно' );
        }

        return ApiResponse::success( [ 'user' => null, 'token' => null ] );
    }
}

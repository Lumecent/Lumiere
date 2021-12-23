<?php

namespace App\Abstractions\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as IlluminateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

abstract class Request extends IlluminateRequest
{
    protected function failedValidation( Validator $validator ): void
    {
        $errors = ( new ValidationException( $validator ) )->errors();

        throw new HttpResponseException( response()->json( [
            'message' => 'Введены некорректные данные',
            'errors' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY ) );
    }
}

<?php

namespace App\Abstractions\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as IlluminateRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class FormRequest extends IlluminateRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation( Validator $validator ): void
    {
        $errors = ( new ValidationException( $validator ) )->errors();

        throw new HttpResponseException( response()->json( [
            'message' => 'Введены некорректные данные',
            'errors' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY ) );
    }
}

<?php

namespace App\Abstractions\Http\Requests;

use App\Abstractions\Http\UploadedFile;
use App\Abstractions\Testing\File;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as IlluminateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class FormRequest extends IlluminateRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retrieve a file from the request.
     *
     * @param null $key
     * @param mixed $default
     * @return UploadedFile|File|array|null
     */
    public function file( $key = null, $default = null ): UploadedFile|File|array|null
    {
        return data_get($this->allFiles(), $key, $default);
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

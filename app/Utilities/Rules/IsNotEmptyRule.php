<?php

namespace App\Utilities\Rules;

use App\Utilities\Helpers\StringHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsNotEmptyRule implements ValidationRule
{
    public function validate( ?string $attribute, mixed $value, Closure $fail ): void
    {
        if ( !$attribute || mb_strlen( str_replace( ' ', '', StringHelper::removeSpaces( $value ) ) ) ) {
            $fail( ':attribute не может состоять только из знаков пробела' );
        }
    }
}

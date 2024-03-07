<?php

namespace App\Abstractions\Exception;

use Illuminate\Support\Facades\Log;
use Throwable;

abstract class Exception extends \Exception
{
    public function __construct( string $message, int $code = 500, ?Throwable $exception = null )
    {
        Log::error( 'log', [ $message ] );

        return parent::__construct( $message, $code, $exception );
    }
}
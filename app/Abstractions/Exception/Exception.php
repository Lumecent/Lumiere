<?php

namespace App\Abstractions\Exception;

use Throwable;

abstract class Exception extends \Exception
{
    public function __construct(string $message, int $code = 500, ?Throwable $exception = null)
    {
        return parent::__construct($message, $code, $exception);
    }
}
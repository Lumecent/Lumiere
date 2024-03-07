<?php

namespace App\Abstractions\Traits;

use Illuminate\Database\Eloquent\SoftDeletes as IlluminateSoftDeletes;

trait SoftDeletesTrait
{
    use IlluminateSoftDeletes;
}
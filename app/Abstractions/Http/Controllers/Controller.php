<?php

namespace App\Abstractions\Http\Controllers;

use App\Utilities\Facades\AuthUser;
use Illuminate\Routing\Controller as IlluminateController;

abstract class Controller extends IlluminateController
{
    public function __construct()
    {
        resolve( AuthUser::class );

        AuthUser::clear();
    }
}

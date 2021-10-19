<?php

namespace App\Containers\User\Models;

use App\Abstractions\Models\Auth;
use App\Containers\User\Data\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Auth
{
    use HasFactory, Notifiable;

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}

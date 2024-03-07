<?php

namespace App\Containers\User\Models;

use App\Abstractions\Database\Models\Auth;
use App\Abstractions\Database\Relations\MorphOne;
use App\Abstractions\Traits\HasFactoryTrait;
use App\Containers\AuthSession\Models\AuthSession;
use App\Containers\User\Data\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property-read AuthSession $session
 */
class User extends Auth
{
    use HasFactoryTrait, Notifiable;

    protected $guarded = [
        'password',
    ];

    protected $hidden = [
        'remember_token',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function session(): MorphOne
    {
        return $this->morphOne( AuthSession::class, 'model' );
    }
}

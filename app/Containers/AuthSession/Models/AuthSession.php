<?php

namespace App\Containers\AuthSession\Models;

use App\Abstractions\Database\Models\Model;
use App\Containers\AuthSession\Data\Factories\AuthSessionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $user_agent
 * @property string $model_type
 * @property int $model_id
 * @property string $ip
 * @property string $token
 * @property Carbon $created_at
 * @property Carbon $expired_at
 */
class AuthSession extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    protected static function newFactory(): AuthSessionFactory
    {
        return new AuthSessionFactory();
    }
}

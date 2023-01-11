<?php

namespace App\Models;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\SignupActivate;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $user_activation_key
 * @property string $full_name
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'user_activation_key',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function services(): hasMany
    {
        return $this->hasMany(Service::class);
    }

    public function scopeWhereEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    public function scopeWhereActivationToken(Builder $query, string $token): Builder
    {
        return $query->where('user_activation_key', $token);
    }


    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token, $this->email));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new SignupActivate());
    }

}

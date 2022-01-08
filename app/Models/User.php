<?php

namespace App\Models;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\SignupActivate;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property mixed $email
 * @property mixed|null $email_verified_at
 * @property mixed|\Ramsey\Uuid\UuidInterface|null $user_activation_key
 * @property mixed|string $password
 * @property array|\ArrayAccess|mixed $full_name
 */
class User extends Authenticatable implements MustVerifyEmail

{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'email_verified_at',
        'user_activation_key',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Support\Collection
     */
    public function permissions(): Collection
    {
        return $this->role->permissions()->pluck('name');
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return true;
        return in_array($permission, $this->permissions()->toArray());
    }

    public function hasPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission))
                return true;
        }
        return false;
    }


    /**
     * @param Builder $query
     * @param string $email
     * @return Builder
     */
    public function scopeWhereEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    /**
     * @param Builder $query
     * @param string $email
     * @param string $token
     * @return Builder|null
     */
    public function scopeWherePasswordResetToken(Builder $query, string $email, string $token): Builder|null
    {
        if (DB::table('password_resets')->where('email', $email)->where('token', $token)->where('password_reset_token_expires_at', '>=', now())->exists())
            return $query->where('email', $email);
        return null;

    }

    /**
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role()->first()->id == $role;
    }

    /**
     *
     * @param array $roles
     * @return bool
     */
    public function hasRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) return true;
        }
        return false;
    }

    /**
     * @param Builder $query
     * @param string $token
     * @return Builder
     */
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

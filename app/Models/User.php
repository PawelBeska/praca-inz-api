<?php

namespace App\Models;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\SignupActivate;
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
 * @property string $id
 * @property mixed $email
 * @property mixed|null $email_verified_at
 * @property mixed|\Ramsey\Uuid\UuidInterface|null $user_activation_key
 * @property mixed|string $password
 * @property array|\ArrayAccess|mixed $full_name
 * @property mixed $role_id
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'role_id',
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

    public function permissions(): Collection
    {
        return $this->role->permissions()->pluck('name');
    }

    public function hasPermission(string $permission): bool
    {
        return true;
        return in_array($permission, $this->permissions()->toArray());
    }

    public function hasPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function scopeWhereEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    public function hasRole(string $role): bool
    {
        return $this->role()->first()->id == $role;
    }

    public function hasRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
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

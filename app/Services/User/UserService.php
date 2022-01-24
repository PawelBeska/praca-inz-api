<?php

namespace App\Services\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserService
{

    private User $user;

    /**
     * @param User|null $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user ? $user : new User();
    }

    /**
     * @param array $data
     * @param Role|null $role
     * @return User
     */
    public function assignData(array $data, Role $role = null): User
    {
        $this->user->role_id = $role ? $role->id : Role::getDefaultRole()->id;
        $this->user->email = Arr::get($data, 'email', $this->user->email);
        $this->user->full_name = Arr::get($data, 'email', $this->user->email);
        if (Arr::get($data, 'email', false))
            $this->user->password = bcrypt(Arr::get($data, 'password'));

        $this->user->email_verified_at = array_key_exists('email_verified_at', $data) ? $data['email_verified_at'] : null;
        $this->user->user_activation_key = array_key_exists('email_verified_at', $data) ? null : Str::uuid();

        $this->user->save();
        return $this->user;
    }

}

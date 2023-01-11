<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserService
{
    public function __construct(private Authenticatable|User $user = new User())
    {
    }

    public function setInstance(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param array $data
     * @return User
     */
    public function assignData(array $data): User
    {
        $this->user->email = Arr::get($data, 'email', $this->user->email);
        $this->user->full_name = Arr::get($data, 'full_name', $this->user->email);
        if (Arr::get($data, 'password', false)) {
            $this->user->password = bcrypt(Arr::get($data, 'password'));
        }

        $this->user->email_verified_at = Arr::get($data, 'email_verified_at');
        $this->user->user_activation_key = Arr::get($data, 'email_verified_at') ?? Str::uuid();

        $this->user->save();
        return $this->user;
    }

}

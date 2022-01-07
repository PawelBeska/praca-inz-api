<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Arr;

class UserService
{

    /**
     * @param User|null $user
     */
    public function __construct(private ?User $user = new User())
    {}

    /**
     * @param array $data
     * @return User
     */
    public function assignData(array $data): User
    {
        $this->user->email = Arr::get($data,'email',$this->user->email);
        $this->user->save();
        return $this->user;
    }

}

<?php


namespace App\Services\Role;

use App\Models\Role;
use Illuminate\Support\Arr;

class RoleService
{

    private Role $role;

    public function __construct(Role $role = null)
    {
        $this->role = $role ? $role : new Role();
    }

    public function assignData(array $data): Role
    {
        $this->role->name = $data['name'];
        $this->role->slug = $data['slug'];
        $this->role->description = Arr::get($data, 'description', $this->role->description);
        $this->role->save();
        return $this->role;
    }
}
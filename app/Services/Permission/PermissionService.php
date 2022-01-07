<?php

namespace App\Services\Permission;

use App\Models\Permission;

class PermissionService
{

    /**
     * @var Permission
     */
    private Permission $permission;

    /**
     * @param Permission|null $permission
     */
    public function __construct(Permission $permission = null)
    {
        $this->permission = $permission ? $permission : new Permission();
    }

    /**
     * @param array $data
     * @return Permission
     */
    public function assignData(array $data): Permission
    {
        $this->permission->name = $data['name'];
        $this->permission->slug = $data['slug'];
        $this->permission->save();
        return $this->permission;

    }

    
}
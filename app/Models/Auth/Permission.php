<?php

namespace App\Models\Auth;

use App\Models\BaseModel;

class Permission extends BaseModel
{
    protected $table = 'auth_permissions';

    public function roles()
    {
        return $this->belongsToMany(
            Role::class, 
            'auth_role_permissions', 
            'permission_id', 
            'role_id'
        );
    }
}

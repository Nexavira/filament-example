<?php

namespace App\Models\Auth;

use App\Models\BaseModel;

class Role extends BaseModel
{
    protected $table = 'auth_roles';

    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class, 
            'auth_role_permissions', 
            'role_id', 
            'permission_id'
        ); 
    }
}

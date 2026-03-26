<?php

namespace App\Models\Auth;

use App\Models\BaseModel;

class Role extends BaseModel
{
    protected $table = 'roles';

    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'id');
    }
}

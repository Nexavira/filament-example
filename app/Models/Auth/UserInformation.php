<?php

namespace App\Models\Auth;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserInformation extends BaseModel
{
    protected $table = 'auth_user_informations'; 

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

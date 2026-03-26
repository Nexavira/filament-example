<?php

namespace App\Models\Auth;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class UserRole extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, Blameable, HasUuids;

    protected $table = 'user_roles';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    protected $hidden = 
    [   
        'id',
        'created_by',
        'updated_by',
        'deleted_by',
        'updated_at',
        'deleted_at',
        'version'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
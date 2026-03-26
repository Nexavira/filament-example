<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    use HasUuids;

    protected $table = 'user_informations'; 

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
    ];

    public $timestamps = false;

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}

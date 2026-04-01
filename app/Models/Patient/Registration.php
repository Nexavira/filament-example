<?php

namespace App\Models\Patient;

use App\Models\Auth\User;
use App\Models\BaseModel;
use App\Models\Patient\Patient;

class Registration extends BaseModel
{
    protected $table = 'registrations';

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicalStaff()
    {
        return $this->belongsTo(User::class, 'medical_staff_id');
    }
}

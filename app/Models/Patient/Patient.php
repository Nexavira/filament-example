<?php

namespace App\Models\Patient;

use App\Models\BaseModel;

class Patient extends BaseModel
{
    protected $table = 'patients';

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}

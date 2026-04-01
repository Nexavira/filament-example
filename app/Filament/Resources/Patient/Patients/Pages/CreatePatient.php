<?php

namespace App\Filament\Resources\Patient\Patients\Pages;

use App\Filament\Resources\Patient\Patients\PatientResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;
}

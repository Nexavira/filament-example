<?php

namespace App\Filament\Resources\Patient\Registrations\Pages;

use App\Filament\Resources\Patient\Registrations\RegistrationResource;
use App\Models\Patient\Registration;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateRegistration extends CreateRecord
{
    protected static string $resource = RegistrationResource::class;
}

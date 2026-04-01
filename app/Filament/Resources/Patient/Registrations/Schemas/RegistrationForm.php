<?php

namespace App\Filament\Resources\Patient\Registrations\Schemas;

use Dom\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('patient_id')
                    ->label('Nama Pasien')
                    ->relationship('patient', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('medical_record_number')
                            ->label('Medical Record Number')
                            ->required(),
                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required(),
                        TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->required(),
                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->nullable(),
                        TextInput::make('address')
                            ->label('Address')
                            ->nullable(),
                        Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'M' => 'Male',
                                'F' => 'Female',
                            ])
                            ->native(false)
                            ->searchable()
                            ->nullable(),
                    ]),
                Select::make('complaint_category')
                    ->label('Complaint Category')
                    ->options([
                        'patah_tulang' => 'Patah Tulang',
                        'dislokasi' => 'Dislokasi',
                        'salah_urat' => 'Salah Urat',
                        'lainnya' => 'Lainnya',
                    ])
                    ->native(false)
                    ->searchable()
                    ->required(),
                Textarea::make('complaint_description')
                    ->label('Complaint Description')
                    ->nullable(),
            ]);
    }
}

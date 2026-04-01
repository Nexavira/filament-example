<?php

namespace App\Filament\Resources\Patient\Patients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        $operation = $schema->getOperation();

        return match ($operation) {
            'create' => self::getCreateSchema($schema),
            'edit'   => self::getEditSchema($schema),
            'view'   => self::getViewSchema($schema),
            default  => $schema,
        };
    }

    protected static function getViewSchema(Schema $schema): Schema
    {
        return $schema->components([
            Group::make([
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required(),
                TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->unique(
                        table: 'patients', 
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            return $rule->whereNull('deleted_at');
                        }
                    ),
                TextInput::make('phone_number')
                        ->label('Phone Number')
                        ->tel(),
                DatePicker::make('date_of_birth')
                    ->label('Date of Birth')
                    ->required(),
                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                    ])
                    ->required(),
                Textarea::make('address')
                    ->label('Address')
            ])->columnSpanFull()
        ]);
    }

    /**
     * Skema Khusus Halaman Create
     */
    protected static function getCreateSchema(Schema $schema): Schema
    {
        return $schema->components([
            Group::make([
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required(),
                TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->unique(
                        table: 'patients', 
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            return $rule->whereNull('deleted_at');
                        }
                    ),
                TextInput::make('phone_number')
                        ->label('Phone Number')
                        ->tel(),
                DatePicker::make('date_of_birth')
                    ->label('Date of Birth')
                    ->required(),
                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                    ])
                    ->required(),
                Textarea::make('address')
                    ->label('Address')
            ])->columnSpanFull()
        ]);
    }

    /**
     * Skema Khusus Halaman Edit
     */
    protected static function getEditSchema(Schema $schema): Schema
    {
        return $schema->components([
            Group::make([
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required(),
                TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->unique(
                        table: 'patients', 
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            return $rule->whereNull('deleted_at');
                        }
                    ),
                TextInput::make('phone_number')
                        ->label('Phone Number')
                        ->tel(),
                DatePicker::make('date_of_birth')
                    ->label('Date of Birth')
                    ->required(),
                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                    ])
                    ->required(),
                Textarea::make('address')
                    ->label('Address')
            ])->columnSpanFull()
        ]);
    }
}

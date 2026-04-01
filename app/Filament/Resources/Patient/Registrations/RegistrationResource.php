<?php

namespace App\Filament\Resources\Patient\Registrations;

use App\Filament\Resources\Patient\Registrations\Pages\CreateRegistration;
use App\Filament\Resources\Patient\Registrations\Pages\EditRegistration;
use App\Filament\Resources\Patient\Registrations\Pages\ListRegistrations;
use App\Filament\Resources\Patient\Registrations\Pages\ViewRegistration;
use App\Filament\Resources\Patient\Registrations\Schemas\RegistrationForm;
use App\Filament\Resources\Patient\Registrations\Schemas\RegistrationInfolist;
use App\Filament\Resources\Patient\Registrations\Tables\RegistrationsTable;
use App\Models\Patient\Registration;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $modelLabel = 'Registration';
    protected static ?string $pluralModelLabel = 'Registrations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'Registration';

    protected static string|UnitEnum|null $navigationGroup = 'Patient Management';

    public static function form(Schema $schema): Schema
    {
        return RegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegistrationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistrations::route('/registration'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereDate('visit_date', now())
            ->orderByRaw("
                CASE 
                    WHEN status = 'in_room' THEN 1 
                    WHEN status = 'waiting' THEN 2 
                    ELSE 3 
                END ASC
            ")
            ->orderBy('queue_number', 'asc');
    }
}

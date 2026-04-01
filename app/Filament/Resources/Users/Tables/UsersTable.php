<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\Auth\Role;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_id')
                    ->label('Profile Picture')
                    ->circular()
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('userInformation.full_name')
                    ->label('Full Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('userInformation.phone_number')
                    ->label('Phone Number')
                    ->searchable(),
                TextColumn::make('userRole.role.name')
                    ->label('Role')
                    ->badge()
                    ->color('info')
                    ->default('No Role Assigned'),
            ])
            ->filters([
                TrashedFilter::make(),
                // SelectFilter::make('role')
                //     ->label('Role')
                //     ->relationship('userRole', 'role_id')
                //     ->options(
                //         Role::all()->pluck('name', 'id')->toArray()
                //     )
                //     ->native(false)
                //     ->searchable() 
                //     ->preload()
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

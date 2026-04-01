<?php

namespace App\Filament\Resources\Patient\Registrations\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('queue_number')
                    ->label('Queue Number')
                    ->searchable()
                    ->default('-')
                    ->badge()
                    ->color('primary')
                    ->alignCenter(),
                TextColumn::make('patient.full_name')
                    ->label('Full Name')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'waiting' => 'warning',
                        'completed' => 'success',
                        'in_room' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'waiting' => 'Menunggu',
                        'in_room' => 'Di Ruangan',
                        'completed' => 'Selesai',
                        default => $state,
                    }),
                TextColumn::make('medicalStaff.name')
                    ->label('Petugas Medis')
                    ->placeholder('Menunggu Panggilan')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('complete_session')
                    ->label('Selesaikan')
                    ->color('success')
                    ->icon('heroicon-m-check-circle')
                    ->visible(fn ($record) => $record->status === 'in_room')
                    ->requiresConfirmation()
                    ->modalHeading('Selesaikan Pemeriksaan?')
                    ->action(function ($record) {
                        $record->update(['status' => 'completed']);
                        
                        Notification::make()
                            ->title('Pasien Selesai')
                            ->success()
                            ->send();
                    }),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

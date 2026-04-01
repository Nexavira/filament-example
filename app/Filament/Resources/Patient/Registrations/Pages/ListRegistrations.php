<?php

namespace App\Filament\Resources\Patient\Registrations\Pages;

use App\Filament\Resources\Patient\Registrations\RegistrationResource;
use App\Filament\Resources\Patient\Registrations\Widgets\QueueOverview;
use App\Models\Auth\User; // Pastikan ini mengarah ke model User yang benar
use App\Models\Patient\Registration;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('next_patient')
                ->label('Panggil Berikutnya')
                ->icon('heroicon-m-megaphone')
                ->color('primary')
                ->requiresConfirmation()
                
                ->modalHeading(function () {
                    $next = Registration::whereDate('visit_date', now())
                        ->where('status', 'waiting')
                        ->orderBy('queue_number', 'asc')
                        ->first();

                        return $next 
                            ? new HtmlString("Panggil Antrean {$next->queue_number}: <br> {$next->patient->full_name}") 
                            : "Antrean Kosong";
                            // return $next ? "Panggil Antrean <br> {$next->queue_number}:  {$next->patient->full_name}?" : "Antrean Kosong";
                })

                ->modalDescription(function () {
                    $next = Registration::whereDate('visit_date', now())
                        ->where('status', 'waiting')
                        ->orderBy('queue_number', 'asc')
                        ->first();

                    if (!$next) {
                        return 'Sudah tidak ada pasien yang menunggu panggilan hari ini.';
                    }

                    return 'Pilih petugas medis. Jika petugas yang dipilih sedang menangani pasien, status pasien tersebut otomatis diubah menjadi Selesai.';
                })

                ->form([
                    Select::make('medical_staff_id')
                        ->label('Pilih Petugas Medis')
                        ->options(
                            User::with('userInformation')
                                ->whereHas('userRole', function (Builder $query) {
                                    $query->where('role_id', 2);
                                })
                                ->get()
                                ->pluck('userInformation.full_name', 'id')
                        )
                        ->required()
                        ->searchable()
                        ->hidden(fn () => !Registration::whereDate('visit_date', now())->where('status', 'waiting')->exists()),
                ])

                ->modalSubmitAction(fn ($action) => 
                    $action->hidden(fn () => !Registration::whereDate('visit_date', now())->where('status', 'waiting')->exists())
                )

                ->modalCancelAction(fn ($action) => 
                    $action->label(fn () => Registration::whereDate('visit_date', now())->where('status', 'waiting')->exists() ? 'Batal' : 'Tutup')
                )

                ->action(function (array $data) {
                    $today = now()->format('Y-m-d');
                    
                    $next = Registration::whereDate('visit_date', $today)
                        ->where('status', 'waiting')
                        ->orderBy('queue_number', 'asc')
                        ->first();

                    if (!$next) {
                        return;
                    }

                    $staffId = $data['medical_staff_id'];

                    $activePatient = Registration::whereDate('visit_date', $today)
                        ->where('medical_staff_id', $staffId)
                        ->where('status', 'in_room')
                        ->first();

                    if ($activePatient) {
                        $activePatient->update(['status' => 'completed']);
                    }

                    $next->update([
                        'status' => 'in_room',
                        'medical_staff_id' => $staffId,
                    ]);

                    Notification::make()
                        ->title("Antrean {$next->queue_number} Berhasil Dipanggil")
                        ->body("Pasien diarahkan ke petugas medis terpilih.")
                        ->success()
                        ->send();
                    
                    return redirect(request()->header('Referer'));
                }),

            CreateAction::make()
                ->label('Pendaftaran Baru')
                ->outlined()
                ->using(function (array $data, string $model): Model {
                    $today = now()->format('Y-m-d');
                    
                    $data['visit_date'] = $today;
                    
                    $maxQueue = $model::whereDate('visit_date', $today)->max('queue_number');
                    $data['queue_number'] = $maxQueue ? $maxQueue + 1 : 1;
                    
                    $data['status'] = 'waiting';

                    return $model::create($data);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            QueueOverview::class,
        ];
    }
}
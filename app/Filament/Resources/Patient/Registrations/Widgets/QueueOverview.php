<?php

namespace App\Filament\Resources\Patient\Registrations\Widgets;

use App\Models\Patient\Registration;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class QueueOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = now(); 

        $current = Registration::whereDate('visit_date', $today)
            ->where('status', 'in_room')
            ->first();

        $next = Registration::whereDate('visit_date', $today)
            ->where('status', 'waiting')
            ->orderBy('queue_number', 'asc')
            ->first();
            
        $totalCount = Registration::whereDate('visit_date', $today)->count();

        return [
            Stat::make('Sedang Dilayani', $current?->queue_number ?? '-')
                ->description($current?->patient?->full_name ?? 'Belum ada pasien')
                ->descriptionIcon('heroicon-m-user', IconPosition::Before)
                ->icon('heroicon-o-speaker-wave')
                ->color('success'),
                
            Stat::make('Antrean Berikutnya', $next?->queue_number ?? '-')
                ->description($next?->patient?->full_name ?? 'Antrean kosong')
                ->descriptionIcon('heroicon-m-clock', IconPosition::Before)
                ->icon('heroicon-o-users')
                ->color('warning'),
            
            Stat::make('Total Antrean', $totalCount)
                ->description($today->translatedFormat('l, d F Y')) 
                ->descriptionIcon('heroicon-m-calendar-days', IconPosition::Before)
                ->icon('heroicon-o-clipboard-document-check')
                ->color('primary'),
        ];
    }
}
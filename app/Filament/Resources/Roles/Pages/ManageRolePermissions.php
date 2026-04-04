<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Roles\Schemas\RolePermissionForm;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema; 

class ManageRolePermissions extends Page implements HasForms
{
    use InteractsWithRecord, InteractsWithForms;

    protected static string $resource = RoleResource::class;

    protected string $view = 'filament.resources.roles.pages.manage-role-permissions';

    public ?array $data = [];

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->form->fill([
            'permissions' => $this->record->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray(),
        ]);
    }

    public function form(Schema $form): Schema
    {
        return RolePermissionForm::configure($form)
            ->statePath('data');
    }

    public function getTitle(): string 
    {
        return "Manage Permissions: " . ($this->record->name ?? 'Role') . " Role";
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->icon('heroicon-m-check-badge')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Perubahan Hak Akses')
                ->modalDescription(fn() => $this->getDiffDescription())
                ->modalSubmitActionLabel('Ya, Simpan & Update Database')
                ->action(function () {
        try {
            $rawPermissions = $this->data['permissions'] ?? [];

            $permissionIds = collect($rawPermissions)
                ->flatten()
                ->filter()
                ->unique()
                ->toArray();

            $this->record->permissions()->sync($permissionIds);

            $this->record->load('permissions');

            \Filament\Notifications\Notification::make()
                ->title('Berhasil Diperbarui')
                ->success()
                ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Sinkronisasi')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function save(): void
    {
        try {
            $state = $this->form->getRawState(); 
            $permissionIds = $state['permissions'] ?? [];

            $permissionIds = array_map('intval', $permissionIds);

            $this->record->permissions()->sync($permissionIds);

            $this->record->load('permissions');

            Notification::make()
                ->title('Berhasil!')
                ->success()
                ->send();

            } catch (\Exception $e) {
                Notification::make()
                    ->title('Terjadi Kesalahan')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        }

        protected function getDiffDescription(): \Illuminate\Support\HtmlString
    {
        $currentSelection = collect($this->data['permissions'] ?? [])->flatten()->toArray();
        $originalSelection = $this->record->permissions->pluck('id')->toArray();

        $addedIds = array_diff($currentSelection, $originalSelection);
        $removedIds = array_diff($originalSelection, $currentSelection);

        if (empty($addedIds) && empty($removedIds)) {
            return new \Illuminate\Support\HtmlString("<div style='text-align:center; padding:20px; color:#666;'>Tidak ada perubahan.</div>");
        }

        $renderContent = function($ids, $color) {
            if (empty($ids)) return "<p style='font-size:12px; color:#999; font-style:italic;'>Tidak ada data.</p>";

            $items = \App\Models\Auth\Permission::whereIn('id', $ids)
                ->get()
                ->groupBy(['guard_name', 'module_label']);

            $html = "<div style='display:flex; flex-direction:column; gap:10px; text-align:left;'>";
            foreach ($items as $guard => $modules) {
                foreach ($modules as $module => $permissions) {
                    $actions = $permissions->pluck('action_label')->implode(', ');
                    $html .= "
                    <div style='border:1px solid #eee; border-radius:8px; padding:10px; background:#fafafa;'>
                        <div style='display:flex; align-items:center; gap:8px; margin-bottom:4px;'>
                            <span style='background:#333; color:white; font-size:10px; padding:2px 6px; border-radius:4px; font-weight:bold;'>" . strtoupper($guard) . "</span>
                            <span style='font-weight:bold; font-size:13px; color:#333;'>$module</span>
                        </div>
                        <div style='font-size:12px; color:$color;'>$actions</div>
                    </div>";
                }
            }
            $html .= "</div>";
            return $html;
        };

        $addedContent = $renderContent($addedIds, '#16a34a'); // Hijau
        $removedContent = $renderContent($removedIds, '#dc2626'); // Merah

        $finalHtml = "
        <div style='margin-top:15px; border:1px solid #eee; border-radius:12px; overflow:hidden;'>
            <div style='background:#f3f4f6; padding:10px; font-weight:bold; border-bottom:1px solid #eee; color:#16a34a; text-align:left;'>
                [+] IZIN DITAMBAHKAN (" . count($addedIds) . ")
            </div>
            <div style='padding:15px; max-height:200px; overflow-y:auto; background:white;'>
                $addedContent
            </div>
            
            <div style='background:#f3f4f6; padding:10px; font-weight:bold; border-top:1px solid #eee; border-bottom:1px solid #eee; color:#dc2626; text-align:left;'>
                [-] IZIN DICABUT (" . count($removedIds) . ")
            </div>
            <div style='padding:15px; max-height:200px; overflow-y:auto; background:white;'>
                $removedContent
            </div>
        </div>";

        return new \Illuminate\Support\HtmlString($finalHtml);
    }
}
<?php

namespace App\Filament\Resources\Users\Schemas;

use Dom\Text;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;

class UserForm
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
            Grid::make(1)
                ->schema([
                    FileUpload::make('photo_id')
                        ->label('Foto Profil')
                        ->image()
                        ->avatar()
                        ->directory('profile-photos')
                        ->alignCenter()
                        ->disabled(),
                ])->columnSpanFull(),
            Grid::make(2)
                ->schema([
                    Group::make([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->disabled(),
                        TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->disabled(),
                        Select::make('role')
                            ->label('Peran')
                            ->options([
                                'admin' => 'Administrator',
                                'user' => 'Reguler User',
                            ])
                            ->native(false)
                            ->searchable() 
                            ->preload()
                            ->extraAttributes([
                                'style' => 'cursor: not-allowed !important;',
                            ])
                            ->disabled(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->disabled(),
                    ])->relationship('userInformation'),
                    Group::make([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->disabled(),
                    ])
                ])->columnSpanFull(),    
        ]);
    }

    /**
     * Skema Khusus Halaman Create
     */
    protected static function getCreateSchema(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(1)
                ->schema([
                    FileUpload::make('photo_id')
                        ->label('Foto Profil')
                        ->image()
                        ->avatar()
                        // ->imageEditor(true)
                        ->directory('profile-photos')
                        ->alignCenter(),
            ])->columnSpanFull(),
            Grid::make(2)
                ->schema([
                    Group::make([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required(),
                        TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel(),
                        Select::make('role')
                            ->label('Peran')
                            ->options([
                                'admin' => 'Administrator',
                                'user' => 'Reguler User',
                            ])
                            ->native(false)
                            ->searchable() 
                            ->preload()
                            ->extraAttributes([
                                'style' => 'cursor: pointer !important;',
                            ]),
                            // ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ])->relationship('userInformation'),
                    Group::make([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(
                                table: 'users', 
                                ignoreRecord: true,
                                modifyRuleUsing: function ($rule) {
                                    return $rule->whereNull('deleted_at');
                                }
                            ),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->required()
                            ->revealable()
                            ->dehydrated(false)
                            ->same('password')
                            // ->helperText(new HtmlString('<span style="color: #dc2626; font-size: 0.875rem;">Masukkan ulang password untuk konfirmasi</span>'))
                    ])
                ])->columnSpanFull(),    
        ]);
    }

    /**
     * Skema Khusus Halaman Edit
     */
    protected static function getEditSchema(Schema $schema): Schema
        {
        return $schema->components([
            Grid::make(1)
                ->schema([
                    FileUpload::make('photo_id')
                        ->label('Foto Profil')
                        ->image()
                        ->avatar()
                        // ->imageEditor(true)
                        ->directory('profile-photos')
                        ->alignCenter(),
            ])->columnSpanFull(),
            Grid::make(2)
                ->schema([
                    Group::make([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required(),
                        TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel(),
                        Select::make('role')
                            ->label('Peran')
                            ->options([
                                'admin' => 'Administrator',
                                'user' => 'Reguler User',
                            ])
                            ->native(false)
                            ->searchable() 
                            ->preload()
                            ->extraAttributes([
                                'style' => 'cursor: pointer !important;',
                            ]),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ])->relationship('userInformation'),
                    Group::make([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(
                                table: 'users', 
                                ignoreRecord: true,
                                modifyRuleUsing: function ($rule) {
                                    return $rule->whereNull('deleted_at');
                                }
                            ),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->same('password')
                            // ->helperText(new HtmlString('<span style="color: #dc2626; font-size: 0.875rem;">Masukkan ulang password untuk konfirmasi</span>'))
                    ])
                ])->columnSpanFull(),    
        ]);
    }
}
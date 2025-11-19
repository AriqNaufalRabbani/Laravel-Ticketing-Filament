<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Grid::make(2)->schema([

                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(User::class, 'email', ignoreRecord: true),

                    Select::make('role')
                        ->label('Role')
                        ->required()
                        ->options([
                            'admin' => 'Admin',
                            'agent' => 'Agent',
                            'user'  => 'User',
                        ])
                        ->native(false),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->nullable() // password opsional saat edit
                        ->dehydrateStateUsing(fn ($state) =>
                            filled($state) ? Hash::make($state) : null
                        )
                        ->dehydrated(fn ($state) => filled($state)) // hanya update password jika diisi
                        ->maxLength(255),
                ]),
            ]);
    }
}

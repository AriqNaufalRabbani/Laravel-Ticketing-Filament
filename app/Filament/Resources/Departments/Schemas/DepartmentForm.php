<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(1)->schema([
                    TextInput::make('name')
                        ->label('Department Name')
                        ->required()
                        ->maxLength(255),
                ]),
            ]);
    }
}

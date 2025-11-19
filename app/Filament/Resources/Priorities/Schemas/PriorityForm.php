<?php

namespace App\Filament\Resources\Priorities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PriorityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([

                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('Priority Name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('level')
                        ->label('Level (1 = Highest Priority)')
                        ->required()
                        ->numeric(),

                    ColorPicker::make('color')
                        ->label('Color')
                        ->columnSpanFull(),
                ])
            ]);
    }
}

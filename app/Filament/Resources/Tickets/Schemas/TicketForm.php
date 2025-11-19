<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Department;
use App\Models\User;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([

                Grid::make(2)->schema([

                    TextInput::make('title')
                        ->required()
                        ->label('Ticket Title')
                        ->maxLength(255)
                        ->columnSpan(2),

                    Textarea::make('description')
                        ->label('Description')
                        ->rows(5)
                        ->columnSpan(2),

                    Select::make('priority_id')
                        ->label('Priority')
                        ->relationship('priority', 'name')
                        ->required()
                        ->searchable()
                        ->columnSpan(1),

                    Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->required()
                        ->searchable()
                        ->columnSpan(1),

                    Select::make('department_id')
                        ->label('Department')
                        ->relationship('department', 'name')
                        ->searchable()
                        ->columnSpan(1),

                    Select::make('requester_id')
                        ->label('Requester')
                        ->relationship('requester', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpan(1),

                    Select::make('assigned_to')
                        ->label('Assigned To')
                        ->relationship('assignedUser', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->columnSpan(1),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'open' => 'Open',
                            'in_progress' => 'In Progress',
                            'resolved' => 'Resolved',
                            'closed' => 'Closed',
                        ])
                        ->default('open')
                        ->required()
                        ->columnSpan(1),
                ]),
            ]);
    }
}

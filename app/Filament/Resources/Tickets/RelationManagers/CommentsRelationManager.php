<?php

namespace App\Filament\Resources\Tickets\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Ticket Comments';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Textarea::make('comment')
                ->label('Comment')
                ->required()
                ->rows(4)
                ->autosize(),

            Forms\Components\FileUpload::make('attachment')
                ->label('Attachment')
                ->directory('ticket-comments')
                ->preserveFilenames()
                ->maxSize(2048)
                ->acceptedFileTypes(['image/*', 'application/pdf']),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->markdown()
                    ->wrap(),

                Tables\Columns\ImageColumn::make('attachment')
                    ->label('Attachment')
                    ->size(60),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}

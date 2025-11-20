<?php

namespace App\Filament\Resources\Tickets\Widgets;

use Filament\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\TicketLog;

class TicketLogWidget extends TableWidget
{
    protected static ?string $heading = 'Ticket Logs';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => TicketLog::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->colors([
                        'primary' => 'status_changed',
                        'warning' => 'priority_changed',
                        'info' => 'assigned',
                        'success' => 'comment',
                    ])
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('old_value')
                    ->label('Old')
                    ->limit(25)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('new_value')
                    ->label('New')
                    ->limit(25)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(40)
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'status_changed' => 'Status Changed',
                        'priority_changed' => 'Priority Changed',
                        'assigned' => 'Assigned',
                        'comment' => 'Comment',
                    ])
            ])
            ->headerActions([])
            ->recordActions([])
            ->bulkActions([
                BulkActionGroup::make([]),
            ]);
    }
}

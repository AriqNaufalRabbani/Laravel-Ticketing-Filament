<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TicketStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected ?string $heading = 'Ticket Summary';

    protected function getCards(): array
    {
        return [
            Stat::make('Total Tickets', Ticket::count())
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary'),

            Stat::make('Open', Ticket::where('status', 'open')->count())
                ->icon('heroicon-o-exclamation-circle')
                ->color('warning'),

            Stat::make('In Progress', Ticket::where('status', 'in_progress')->count())
                ->icon('heroicon-o-arrow-path')
                ->color('info'),

            Stat::make('Resolved', Ticket::where('status', 'resolved')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Closed', Ticket::where('status', 'closed')->count())
                ->icon('heroicon-o-lock-closed')
                ->color('gray'),

            // Optional: Tickets Created Today
            Stat::make('Today', Ticket::whereDate('created_at', today())->count())
                ->icon('heroicon-o-calendar-days')
                ->color('success'),
        ];
    }
}

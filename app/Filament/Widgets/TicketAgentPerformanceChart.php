<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\User;
use Filament\Widgets\ChartWidget;

class TicketAgentPerformanceChart extends ChartWidget
{
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = ['default' => 1, 'lg' => 1];
    protected ?string $maxHeight = '350px';
    protected ?string $heading = 'Tickets per Agent';

    protected function getData(): array
    {
        $agents = User::where('role', 'agent')->pluck('name', 'id')->toArray();

        $counts = Ticket::selectRaw('assigned_to, COUNT(*) as total')
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to')
            ->pluck('total', 'assigned_to')
            ->toArray();

        $colors = $this->dynamicColors(count($agents));

        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => array_map(fn ($id) => $counts[$id] ?? 0, array_keys($agents)),
                    'backgroundColor' => $colors,
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => array_values($agents),
        ];
    }

    protected function dynamicColors($count)
    {
        $palette = [
            '#4ade80', '#60a5fa', '#f472b6', '#facc15',
            '#a78bfa', '#fb7185', '#22d3ee', '#34d399',
            '#f97316', '#2dd4bf', '#c084fc'
        ];

        return array_slice($palette, 0, $count);
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

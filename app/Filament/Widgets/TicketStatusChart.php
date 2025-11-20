<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use App\Traits\FixedChartHeight;

class TicketStatusChart extends ChartWidget
{
    use FixedChartHeight;

    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = ['default' => 1, 'lg' => 1];
    // protected ?string $maxHeight = '350px';

    protected static function chartHeight(): string
    {
        return '300px';
    }

    protected ?string $heading = 'Tickets by Status';

    protected function getData(): array
    {
        $statuses = ['open', 'in_progress', 'resolved', 'closed'];

        $counts = Ticket::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => array_map(fn ($s) => $counts[$s] ?? 0, $statuses),
                    'backgroundColor' => ['#4ade80', '#60a5fa', '#facc15', '#f87171'],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => [
                'Open',
                'In Progress',
                'Resolved',
                'Closed',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

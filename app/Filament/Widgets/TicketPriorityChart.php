<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\Priority;
use Filament\Widgets\ChartWidget;
use App\Traits\FixedChartHeight;

class TicketPriorityChart extends ChartWidget
{
    use FixedChartHeight;

    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = ['default' => 1, 'lg' => 1];
    // protected ?string $maxHeight = '350px';

    protected static function chartHeight(): string
    {
        return '300px';
    }

    protected ?string $heading = 'Tickets by Priority';

    protected function getData(): array
    {
        // Ambil data priority dari database
        $priorities = Priority::pluck('name', 'id')->toArray();

        // Ambil count ticket per priority_id
        $counts = Ticket::selectRaw('priority_id, COUNT(*) as total')
            ->groupBy('priority_id')
            ->pluck('total', 'priority_id')
            ->toArray();

        // Dynamic Neon Colors for Dark Mode
        $colors = $this->dynamicColors(count($priorities));

        return [
            'datasets' => [
                [
                    'label' => 'Priority',
                    'data' => array_map(
                        fn ($id) => $counts[$id] ?? 0,
                        array_keys($priorities)
                    ),
                    'backgroundColor' => $colors,
                    'borderColor' => '#0f172a',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => array_values($priorities),
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
        return 'doughnut';
    }
}

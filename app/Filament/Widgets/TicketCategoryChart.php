<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\Category;
use Filament\Widgets\ChartWidget;

class TicketCategoryChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = ['default' => 1, 'lg' => 1];
    protected ?string $maxHeight = '350px';
    protected ?string $heading = 'Tickets by Category';

    protected function getData(): array
    {
        $categories = Category::pluck('name', 'id')->toArray();

        $counts = Ticket::selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id')
            ->toArray();
        
        $labels = array_values($categories);
        $data = array_map(fn ($id) => $counts[$id] ?? 0, array_keys($categories));

        $colors = $this->dynamicColors(count($labels));

        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
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
        return 'pie';
    }
}

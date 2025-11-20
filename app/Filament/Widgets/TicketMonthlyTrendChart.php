<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TicketMonthlyTrendChart extends ChartWidget
{
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = ['default' => 2, 'lg' => 2]; 
    protected ?string $maxHeight = '350px';
    protected ?string $heading = 'Ticket Trend (Monthly)';

    protected function getData(): array
    {
        $result = Ticket::selectRaw("
                FORMAT(created_at, 'yyyy-MM') AS month,
                COUNT(*) AS total
            ")
            ->groupBy(DB::raw("FORMAT(created_at, 'yyyy-MM')"))
            ->orderBy(DB::raw("FORMAT(created_at, 'yyyy-MM')"))
            ->pluck('total', 'month')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => array_values($result),
                    'backgroundColor' => ['#ff6384', '#36a2eb', '#ffce56'],
                    'borderColor' => ['#ffffff'],
                ],
            ],
            'labels' => array_keys($result),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

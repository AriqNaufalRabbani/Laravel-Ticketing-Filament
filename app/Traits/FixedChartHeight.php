<?php

namespace App\Traits;

trait FixedChartHeight
{
    protected static string $defaultChartHeight = '320px';

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
        ];
    }

    public function getChartWrapperAttributes(): array
    {
        $height = static::chartHeight();

        return [
            'style' => "height: {$height};",
            'class' => "chart-container",
        ];
    }

    protected static function chartHeight(): string
    {
        return static::$defaultChartHeight;
    }
}

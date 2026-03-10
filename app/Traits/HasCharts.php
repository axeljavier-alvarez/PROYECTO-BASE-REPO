<?php
namespace App\Traits;

use App\Services\Chart;

trait HasCharts
{
    /**
     * Mixed Chart: Permite ['name' => 'Ventas', 'type' => 'column', 'data' => [...]]
     */
    public function mixedChart(array $series, array $labels, string $title = '') {
        return Chart::make('line')
            ->series($series)
            ->labels($labels)
            ->set('title.text', $title)
            ->set('stroke.width', [4, 0, 2]);
    }

    public function barChart(array $series, array $labels) {
        return Chart::make('bar')
            ->series($series)
            ->labels($labels)
            ->set('plotOptions.bar.borderRadius', 6);
    } 

    public function donutChart(array $series, array $labels) {
        return Chart::make('donut')
            ->series($series)
            ->labels($labels);
    } 

    public function areaChart(array $series, array $labels) {
        return Chart::make('area')
            ->series($series)
            ->labels($labels);
    }
}
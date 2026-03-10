<?php

namespace App\Livewire;

use App\Traits\HasCharts;
use Livewire\Component;

class Dashboard extends Component
{
    use HasCharts;

    public function render() {
        $series = [
            [
                'name' => 'TEAM A',
                'type' => 'column', // Se dibuja como barra
                'data' => [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30],
            ],
            [
                'name' => 'TEAM B',
                'type' => 'area',   // Se dibuja como línea
                'data' => [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43],
            ],
            [
                'name' => 'TEAM C',
                'type' => 'line',   // Se dibuja como área sombreada
                'data' => [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39],
            ],
        ];

        $labels = [
            '01/01/2003',
            '02/01/2003',
            '03/01/2003',
            '04/01/2003',
            '05/01/2003',
            '06/01/2003',
            '07/01/2003',
            '08/01/2003',
            '09/01/2003',
            '10/01/2003',
            '11/01/2003'
        ];

        $mixed = $this->mixedChart($series, $labels, 'Rendimiento Mensual')
        ->set('tooltip.shared', true)
        ->set('tooltip.intersect', false)
        ->formatter("val => (typeof val !== 'undefined') ? val.toFixed(0) + ' points' : val")
        ->build();
        
        return view('livewire.dashboard', compact('mixed'));
    }
}

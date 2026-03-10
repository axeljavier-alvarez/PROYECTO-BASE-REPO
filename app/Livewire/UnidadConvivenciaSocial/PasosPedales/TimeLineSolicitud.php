<?php

namespace App\Livewire\UnidadConvivenciaSocial\PasosPedales;

use App\Livewire\Traits\DataTableTrait;
use App\Livewire\Traits\Interact;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Expediente;
use Flux\Flux;
use Livewire\Component;

class TimeLineSolicitud extends Component
{
    use DataTableTrait, Interact;

    public ?Expediente $expediente = null;

    public function mount() {
        $this->initializeDataTable();
    }

    public function render() {
        $headers = [
            [ 'index' => 'id', 'label' => 'Expediente #', 'align' => 'center'  ],
            [ 'index' => 'solicitud.primer_nombre', 'label' => 'Solicitante' ],
            [ 'index' => 'solicitud.cui', 'label' => 'Dpi' ],
            [ 'index' => 'solicitud.patente_comercio', 'label' => 'Patente' ],
            [ 'index' => 'solicitud.tipo_persona', 'label' => 'Tipo persona' ],
            [ 'index' => 'solicitud.sede.nombre', 'label' => 'Área solicitada' ],
            [ 'index' => 'latestWorkflow.estado.nombre', 'label' => ' Último estado' ],
            [ 'index' => 'actions', 'label' => '' ],
        ];

        $rows = Expediente::filterAdvance($headers, [
                'search' => $this->search,
                'sort' => [
                    'field' => $this->sortBy, 
                    'direction' => $this->sortDirection
                ],
                'filters' => $this->processFilters(),
            ])->paginate($this->per_page);

        if($rows->isEmpty() && $this->search) {
            $fallbackQuery = Expediente::whereHas('solicitud',function($query) {
                $query->whereNombreCompleto($this->search);
            });

            $rows = $fallbackQuery->paginate($this->per_page);
        }

        return view('livewire.unidad-convivencia-social.pasos-pedales.time-line-solicitud', compact('headers','rows'));
    }

    public function viewTimeLineRequest(int $id) {
        $expediente = Expediente::findOrFail($id);
        $this->expediente = $expediente->load([
            'solicitud.sede',
            'workflows.estado',
            'workflows.user.information',
            'area_sede'
        ]);
        
        Flux::modal('time-line')->show();
    }

    public function resetData () {
        $this->reset('expediente');
        Flux::modals()->close();
    }

}
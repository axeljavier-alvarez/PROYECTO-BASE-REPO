<?php

namespace App\Livewire\UnidadConvivenciaSocial\PasosPedales;

use App\Livewire\Traits\DataTableTrait;
use App\Livewire\Traits\Interact;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Expediente;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AutorizacionSolicitud extends Component
{
    use DataTableTrait, Interact;

    public array $expediente = [];
    public ?string $urlDoc = null;
    public array $navItems = [
        [ 'option' => 1, 'label' => 'Datos del solicitante', 'icon' => 'user-circle'],
        [ 'option' => 2, 'label' => 'Espacio solicitado', 'icon' => 'map'],
        [ 'option' => 3, 'label' => 'Documentos subidos', 'icon' => 'document'],
        [ 'option' => 4, 'label' => 'Cambiar estado', 'icon' => 'arrow-path'],
    ];

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
            [ 'index' => 'latestWorkflow.estado.nombre', 'label' => 'Último estado' ],
            [ 'index' => 'actions', 'label' => '' ],
        ];

        $rows = Expediente::filterAdvance($headers, [
                'search' => $this->search,
                'sort' => [
                    'field' => $this->sortBy, 
                    'direction' => $this->sortDirection
                ],
                'filters' => $this->processFilters(),
            ])->whereHas('latestWorkflow',function ($query){
                $query->whereIn('estado_id',[5]);
            })->paginate($this->per_page);

        if($rows->isEmpty() && $this->search) {
            $fallbackQuery = Expediente::whereHas('solicitud',function($query) {
                $query->whereNombreCompleto($this->search);
            });
            $rows = $fallbackQuery->paginate($this->per_page);
        }
        
        return view('livewire.unidad-convivencia-social.pasos-pedales.autorizacion-solicitud', compact('headers', 'rows'));
    }

    public function viewRequest(int $id) {
        try {

            $expediente = Expediente::findOrFail($id);
            $this->expediente = $expediente->load([
                'area_sede.sede',
                'solicitud.sede',
                'solicitud.documentos',
                'latestWorkflow'
            ])->toArray();
            Flux::modal('revision-solicitud')->show();

        } catch (\Throwable $th) {
            $this->toastError('Error : '.$th->getMessage());
        }
    }

    public function previewDoc(string $url) {
        $this->urlDoc = $url;
    }

    public function rejectRequest() {

        $this->validate([
            'expediente.latestWorkflow.observacion' => 'required|string|max:1000'
        ]);

        try {

            $expediente = Expediente::findOrFail($this->expediente['id']);
            
            $expediente->workflows()->create([
                'observacion' => $this->expediente['latestWorkflow']['observacion'],
                'user_id' => Auth::user()->id,
                'estado_id' => 7
            ]);
            
            $this->toastWarning('Se rechazo la solicitud.');

            $this->resetData();

        } catch (\Throwable $th) {
            $this->toastError('Error : '.$th->getMessage());
        }
    }

    public function rejectAssign() {

        $this->validate([
            'expediente.latestWorkflow.observacion' => 'required|string|max:1000'
        ]);

        try {

            $expediente = Expediente::findOrFail($this->expediente['id']);
            
            $expediente->workflows()->create([
                'observacion' => $this->expediente['latestWorkflow']['observacion'],
                'user_id' => Auth::user()->id,
                'estado_id' => 8
            ]);
            
            $this->toastWarning('Se rechazo la asignación.');

            $this->resetData();

        } catch (\Throwable $th) {
            $this->toastError('Error : '.$th->getMessage());
        }
    }

    public function authorizedRequest() {

        try {

            $expediente = Expediente::findOrFail($this->expediente['id']);
            
            $expediente->workflows()->create([
                'user_id' => Auth::user()->id,
                'estado_id' => 6
            ]);
            
            $this->toastSuccess('Se autorizó la solicitud exitosamente.');

            $this->resetData();

        } catch (\Throwable $th) {
            $this->toastError('Error : '.$th->getMessage());
        }
    }

    public function resetData() {
        $this->reset(['expediente','nav_option','urlDoc']);
        Flux::modals()->close();
        $this->resetErrorBag();
    }
}

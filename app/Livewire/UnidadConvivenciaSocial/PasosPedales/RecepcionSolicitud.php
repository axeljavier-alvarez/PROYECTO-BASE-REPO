<?php

namespace App\Livewire\UnidadConvivenciaSocial\PasosPedales;

use App\Livewire\Traits\DataTableTrait;
use App\Livewire\Traits\Interact;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Expediente;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RecepcionSolicitud extends Component
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
            [ 'index' => 'id', 'label' => 'Expediente #', 'align' => 'center' ],
            [ 'index' => 'solicitud.primer_nombre', 'label' => 'Solicitante' ],
            [ 'index' => 'solicitud.cui', 'label' => 'Dpi' ],
            [ 'index' => 'solicitud.patente_comercio', 'label' => 'Patente' ],
            [ 'index' => 'solicitud.tipo_persona', 'label' => 'Tipo persona' ],
            [ 'index' => 'solicitud.sede.nombre', 'label' => 'Área solicitada' ],
            [ 'index' => 'latestWorkflow.estado.nombre', 'label' => 'Último estado' ],
            [ 'index' => 'solicitud.created_at', 'label' => 'Fecha creación' ],
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
                $query->whereIn('estado_id',[1,2]);
            })->paginate($this->per_page);

        if($rows->isEmpty() && $this->search) {
            $fallbackQuery = Expediente::whereHas('solicitud',function($query) {
                $query->whereNombreCompleto($this->search);
            });
            $rows = $fallbackQuery->paginate($this->per_page);
        }

        return view('livewire.unidad-convivencia-social.pasos-pedales.recepcion-solicitud', compact('headers','rows'));
    }

    public function viewRequest(int $id) {
        try {

            $expediente = Expediente::findOrFail($id);
            $this->reviewingRequest($expediente);
            $this->expediente = $expediente->load([
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

    public function reviewingRequest(Expediente $expediente) {
        try {

            if($expediente->workflows()->where('estado_id',2)->first()) {
                $this->toastInfo('La solicitud ya está en revisión');
                return;
            }

            $expediente->workflows()->create([
                'user_id' => Auth::user()->id,
                'estado_id' => 2
            ]);

            $this->toastSuccess('La solicitud está en revisión.');

        } catch (\Throwable $th) {
            
            $this->toastError('Error : '.$th->getMessage());
        }
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

    public function acceptRequest() {

        $this->validate([
            'expediente.latestWorkflow.observacion' => 'nullable|string|max:1000'
        ]);

        try {

            $expediente = Expediente::findOrFail($this->expediente['id']);
            
            $expediente->workflows()->create([
                'observacion' => $this->expediente['latestWorkflow']['observacion'] ?? null,
                'user_id' => Auth::user()->id,
                'estado_id' => 3
            ]);
            
            $this->toastSuccess('Se acepto solicitud.');

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

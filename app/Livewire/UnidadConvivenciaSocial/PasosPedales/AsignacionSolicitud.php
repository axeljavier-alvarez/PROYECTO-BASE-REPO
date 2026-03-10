<?php

namespace App\Livewire\UnidadConvivenciaSocial\PasosPedales;

use App\Livewire\Traits\DataTableTrait;
use App\Livewire\Traits\Interact;
use App\Models\UnidadConvivenciaSocial\PasosPedales\AreaSede;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Expediente;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Sede;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AsignacionSolicitud extends Component
{
    use DataTableTrait, Interact;

    public array $expediente = [];
    public ?string $urlDoc = null;
    public $areas_sede;
    public string $urlImagen = '';
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
                $query->whereIn('estado_id',[3,4,8]);
            })->paginate($this->per_page);

        if($rows->isEmpty() && $this->search) {
            $fallbackQuery = Expediente::whereHas('solicitud',function($query) {
                $query->whereNombreCompleto($this->search);
            });
            $rows = $fallbackQuery->paginate($this->per_page);
        }

            $sedes = Sede::orderBy('nombre')->get();

        return view('livewire.unidad-convivencia-social.pasos-pedales.asignacion-solicitud', compact('headers', 'rows', 'sedes'));
    }

    public function getAreasSede(int $sede_id) {
        $this->areas_sede = AreaSede::where('sede_id', $sede_id)->orderBy('nombre')->get();
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

    public function previewImage(string $url) {
        $this->urlImagen = $url;
    }

    public function reviewingRequest(Expediente $expediente) {
        try {

            if($expediente->workflows()->where('estado_id',4)->first()) {
                $this->toastInfo('El expediente ya está en revisión');
                return;
            }

            $expediente->workflows()->create([
                'user_id' => Auth::user()->id,
                'estado_id' => 4
            ]);

            $this->toastSuccess('Verificando espacio disponible.');

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

    public function assignSpace() {

        $this->validate([
            'expediente.area_sede_id' => 'required|integer|exists:areas_sede,id',
            'expediente.descripcion' => 'nullable|string|max:1000',
        ]);

        try {

            $expediente = Expediente::findOrFail($this->expediente['id']);

            $expediente->area_sede_id = $this->expediente['area_sede_id'];
            $expediente->descripcion = $this->expediente['descripcion'];
            $expediente->save();
            
            $expediente->workflows()->create([
                'user_id' => Auth::user()->id,
                'estado_id' => 5
            ]);
            
            $this->toastSuccess('Se asigno espacio exitosamente.');

            $this->resetData();

        } catch (\Throwable $th) {
            $this->toastError('Error : '.$th->getMessage());
        }
    }

    public function resetData() {
        $this->reset(['expediente','nav_option','urlDoc','urlImagen','areas_sede']);
        Flux::modals()->close();
        $this->resetErrorBag();
    }
}

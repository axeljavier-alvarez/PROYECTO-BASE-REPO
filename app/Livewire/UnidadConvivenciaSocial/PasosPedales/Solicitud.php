<?php

namespace App\Livewire\UnidadConvivenciaSocial\PasosPedales;

use App\Livewire\Traits\Interact;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Expediente;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Sede;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Solicitud as SolicitudModel;
use App\Models\Zona;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Solicitud extends Component
{
    use Interact, WithFileUploads;

    public array $nueva_solicitud = [
        'documentos' => [
            'carta_solicitud' => null,
            'dpi' => null,
            'rtu' => null,
            'recibo_servicios' => null,
            'patente_comercio' => null,
            'acta_notarial' => null,
        ]
    ];

    public array $steps = [
        'Datos del solicitante',
        'Espacio a solicitar',
        'Documentación'
    ];

    public function render() {
        $sedes = Sede::orderBy('nombre')->get();
        $tipo_personas = SolicitudModel::TIPO_PERSONA;
        $zonas = Zona::all();
        return view('livewire.unidad-convivencia-social.pasos-pedales.solicitud', compact('sedes', 'tipo_personas', 'zonas'));
    }


    public function store() {

        $this->validateCurrentStep();
        
        try {

            DB::beginTransaction();

            $newSolicitud = SolicitudModel::create([
                'primer_nombre' => ucfirst(mb_strtolower($this->nueva_solicitud['primer_nombre'])),
                'segundo_nombre' => ucfirst(mb_strtolower($this->nueva_solicitud['segundo_nombre'])),
                'primer_apellido' => ucfirst(mb_strtolower($this->nueva_solicitud['primer_apellido'])),
                'segundo_apellido' => ucfirst(mb_strtolower($this->nueva_solicitud['segundo_apellido'])),
                'cui' => $this->nueva_solicitud['cui'],
                'nit' => $this->nueva_solicitud['nit'],
                'patente_comercio' => $this->nueva_solicitud['patente_comercio'],
                'telefono' => $this->nueva_solicitud['telefono'],
                'correo' => mb_strtolower($this->nueva_solicitud['correo']),
                'zona_id' => $this->nueva_solicitud['zona_id'] ?? null,
                'colonia' => $this->nueva_solicitud['colonia'] ?? null,
                'domicilio' => $this->nueva_solicitud['domicilio'],
                'actividad_negocio' => $this->nueva_solicitud['actividad_negocio'],
                'largo' => $this->nueva_solicitud['largo'],
                'ancho' => $this->nueva_solicitud['ancho'],
                'observaciones' => $this->nueva_solicitud['observaciones'] ?? null,
                'sede_id' => $this->nueva_solicitud['sede_id'],
                'tipo_persona' => $this->nueva_solicitud['tipo_persona'],
            ]);
                    

            if($newSolicitud) {

                foreach($this->nueva_solicitud['documentos'] as $key => $documento) {
                
                    if(is_null($documento)) {
                        continue;
                    }

                    $newSolicitud->documentos()->create([
                        'nombre' => ucfirst(str_replace("_"," ",$key)),
                        'path' => $this->nueva_solicitud['documentos'][$key]->store('UnidadConvivenciaSocial/PasosPedales/Solicitudes'),
                    ]);
                }

                
                $expediente = Expediente::create([
                    'solicitud_id' => $newSolicitud->id
                ]);

                if($expediente) {
                    $expediente->workflows()->create([
                        'estado_id' => 1
                    ]);
                }
                DB::commit();
                $this->toastSuccess('Solicitud enviada correctamente.');
                $this->resetData();
                return;
            }

            DB::rollBack();
            $this->toastError('Error en el envio de la solicitud.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->toastError('Error:'. $th->getMessage());
        }
    }

    protected function validateCurrentStep(){
        switch ($this->step) {
            case 1:
                $this->validate([
                    'nueva_solicitud.primer_nombre' => 'required|string|max:50',
                    'nueva_solicitud.segundo_nombre' => 'nullable|string|max:50',
                    'nueva_solicitud.primer_apellido' => 'required|string|max:50',
                    'nueva_solicitud.segundo_apellido' => 'nullable|string|max:50',
                    'nueva_solicitud.cui' => 'required|numeric|digits:13',
                    'nueva_solicitud.nit' => 'required|numeric',
                    'nueva_solicitud.patente_comercio' => 'required|string|max:50',
                    'nueva_solicitud.telefono' => 'required|numeric|digits:8',
                    'nueva_solicitud.correo' => 'required|email',
                    'nueva_solicitud.zona_id' => 'nullable|integer|exists:zonas,id',
                    'nueva_solicitud.colonia' => 'nullable|string|max:255',
                    'nueva_solicitud.domicilio' => 'required|string|max:255',
                    'nueva_solicitud.actividad_negocio' => 'required|string',
                    'nueva_solicitud.tipo_persona' => 'required|in:Individual,Juridica',
                ]);
                break;
            case 2:
                $this->validate([
                    'nueva_solicitud.largo' => 'required|numeric',
                    'nueva_solicitud.ancho' => 'required|numeric',
                    'nueva_solicitud.observaciones' => 'nullable|string',
                    'nueva_solicitud.sede_id' => 'required|integer|exists:sedes,id',
                ]);
                break;
            case 3:
                $this->validate([
                    'nueva_solicitud.documentos.carta_solicitud' => 'required|file|mimes:pdf|max:5120',
                    'nueva_solicitud.documentos.dpi' => 'required|file|mimes:pdf|max:5120',
                    'nueva_solicitud.documentos.rtu' => 'required|file|mimes:pdf|max:5120',
                    'nueva_solicitud.documentos.recibo_servicios' => 'required|file|mimes:pdf|max:5120',
                    'nueva_solicitud.documentos.patente_comercio' => 'required|file|mimes:pdf|max:5120',
                    'nueva_solicitud.documentos.acta_notarial' => 'required_if:nueva_solicitud.tipo_persona_id,2|nullable|file|mimes:pdf|max:5120'
                ]);
                break;
        }
    }

    public function resetData() {
        $this->reset(['nueva_solicitud','step']);
    }

}

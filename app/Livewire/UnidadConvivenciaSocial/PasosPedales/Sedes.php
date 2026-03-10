<?php

namespace App\Livewire\UnidadConvivenciaSocial\PasosPedales;

use App\Livewire\Traits\DataTableTrait;
use App\Livewire\Traits\Interact;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Sede;
use Flux\Flux;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Sedes extends Component
{
    use DataTableTrait, 
        Interact, 
        WithFileUploads;
    
    const AREAS_DEFAULT = ['nombre' => '', 'numero' => '', 'path_imagen' => ''];

    public array $sede = [
        'nombre' => '',
        'descripcion' => '',
        'areas' => [self::AREAS_DEFAULT],
    ];

    public ?string $urlImagenArea = null;

    public function render() {
        $headers = [
            ['index' => 'id', 'label' => '#', 'align' => 'center'],
            ['index' => 'nombre', 'label' => 'Nombre'],
            ['index' => 'descripcion', 'label' => 'Descripcion'],
            ['index' => 'areas_count', 'label' => 'Cantidad de áreas', 'align' => 'center'],
            ['index' => 'actions', 'label' => ''],
        ];

        $rows = Sede::with('areas')->withCount('areas')->filterAdvance($headers, [
                'search' => $this->search,
                'sort' => [
                    'field' => $this->sortBy, 
                    'direction' => $this->sortDirection
                ],
                'filters' => $this->processFilters(),
            ])->paginate($this->per_page);

        return view('livewire.unidad-convivencia-social.pasos-pedales.sedes', compact('headers','rows'));
    }

    public function store(){
        $this->validate([
            'sede.nombre' => 'required|string|max:255',
            'sede.descripcion' => 'nullable|string',
            'sede.areas.*.nombre' => 'required|string|max:255',
            'sede.areas.*.path_imagen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {

            DB::beginTransaction(function () {
                
                $sede = Sede::create([
                    'nombre' => ucwords(mb_strtolower($this->sede['nombre'])),
                    'descripcion' => $this->sede['descripcion'] ?? null,
                ]);
    
                if($sede) {
    
                    foreach ($this->sede['areas'] as $area) {
                        if(empty($area['nombre']) || empty($area['path_imagen'])) {
                            continue;
                        }
    
                        $sede->areas()->create([
                            'nombre' => mb_strtoupper($area['nombre']),
                            'path_imagen' => $area['path_imagen']->store('UnidadConvivenciaSocial/PasosPedales/ImagenesAreasSedes'),
                        ]);
                    }
    
                    $this->toastSuccess('Sede creada correctamente');
                    $this->resetData();
                }
            });

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->toastError($th->getMessage());
        }
    }

    public function edit(int $id) {
        $sede = Sede::with('areas')->findOrFail($id);
        $this->sede = $sede->toArray();
        
        if(empty($this->sede['areas'])) {
            $this->sede['areas'] = [self::AREAS_DEFAULT];
        }
        
        Flux::modal('editSede')->show();
    }

    public function update() {
        
        $this->validate([
            'sede.nombre' => 'required|string|max:255',
            'sede.descripcion' => 'nullable|string',
            'sede.areas.*.nombre' => 'required|string|max:255',
            'sede.areas.*.path_imagen' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_string($value) && !($value instanceof UploadedFile)) {
                        $fail("El campo :attribute debe ser una imagen válida o una referencia existente.");
                    }

                    if ($value instanceof UploadedFile) {
                        if (!in_array($value->guessExtension(), ['jpeg', 'png', 'jpg'])) {
                            $fail("La imagen debe ser de tipo: jpeg, png, jpg.");
                        }
                        if ($value->getSize() > 2048 * 1024) {
                            $fail("La imagen no debe pesar más de 2MB.");
                        }
                    }
                },
            ],
        ]);

        try {

            DB::beginTransaction();

            $sede = Sede::with('areas')->findOrFail($this->sede['id']);

            if(!hasChanged($this->sede, $sede->toArray())) {
                $this->resetData();
                return;
            }
            
            $sede->update([
                'nombre' => ucwords(mb_strtolower($this->sede['nombre'])),
                'descripcion' => $this->sede['descripcion'],
            ]);

            $areasData = $this->getValidAreas();
            $areasIds = collect($areasData)->pluck('id')->filter()->toArray();

            $areasToDelete = $sede->areas()->whereNotIn('id', $areasIds)->get();
            foreach ($areasToDelete as $areaOld) {
                if ($areaOld->path_imagen) Storage::delete($areaOld->path_imagen);
                $areaOld->delete();
            }
        
            foreach ($areasData as $area) {
                $existingArea = $sede->areas()->find($area['id'] ?? null);
                $isNewFile = ($area['path_imagen'] instanceof UploadedFile);
                
                $path = $area['path_imagen'];

                if ($isNewFile) {
                    // Borrar imagen anterior si existe
                    if ($existingArea && $existingArea->path_imagen) {
                        Storage::delete($existingArea->path_imagen);
                    }
                    $path = $area['path_imagen']->store('UnidadConvivenciaSocial/PasosPedales/ImagenesAreasSedes');
                }

                $sede->areas()->updateOrCreate(
                    ['id' => $area['id'] ?? null],
                    [
                        'nombre' => mb_strtoupper($area['nombre']),
                        'numero' => $area['numero'] ?? null,
                        'path_imagen' => $path,
                    ]
                );
            }
            DB::commit();
            $this->toastSuccess('Sede y áreas actualizadas correctamente.');
            $this->resetData();

        } catch (\Throwable $th) {
            $this->toastError('Error al actualizar: ' . $th->getMessage());
        }
    }

    public function delete(int $id) {
        $this->sede['id'] = $id;
        Flux::modal('deleteSede')->show();
    }

    public function destroy() {
        try {
            $sede = Sede::with('areas')->findOrFail($this->sede['id']);

            foreach ($sede->areas as $area) {
                if ($area->path_imagen) Storage::delete($area->path_imagen);
            }
            
            $sede->areas()->delete();
            $sede->delete();

            $this->toastSuccess('Sede eliminada correctamente');
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al eliminar la sede');
        }
    }

    public function addArea() {
        $this->sede['areas'][] = self::AREAS_DEFAULT;
    }

    public function deleteArea(int $index) {
        if (count($this->sede['areas']) <= 1) {
            $this->sede['areas'][0] = self::AREAS_DEFAULT;
            return;
        }
        unset($this->sede['areas'][$index]);
        $this->sede['areas'] = array_values($this->sede['areas']);
    }

    private function getValidAreas(): array {
        return collect($this->sede['areas'])
            ->filter(fn($p) => !empty($p['nombre']) && !empty($p['path_imagen']))
            ->toArray();
    }

    public function previewArea(int $id) {
        $sede = Sede::with('areas')->findOrFail($id);
        $this->sede = $sede->toArray();
        Flux::modal('viewAreas')->show();
    }

    public function viewImagenArea(string $url) {
        $this->urlImagenArea = $url;
    }

    public function resetData() {
        $this->reset(['sede', 'urlImagenArea']);
        $this->resetErrorBag();
        Flux::modals()->close();
    }
}

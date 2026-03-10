<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\DataTableTrait;
use App\Livewire\Traits\Interact;
use App\Models\Page;
use App\Models\Permission;
use Flux\Flux;
use Livewire\Component;

class Pages extends Component
{

    use DataTableTrait, Interact;

    public array $page = [];

    public function mount() {
        $this->initializeDataTable();
    }

    public function render() {
        $headers = [
            [ 'index' => 'id', 'label' => '#', 'align' => 'center' ],
            [ 'index' => 'label', 'label' => 'Página' ],
            [ 'index' => 'icon', 'label' => 'Icono' ],
            [ 'index' => 'view', 'label' => 'Vista', 'exclude' => true ],
            [ 'index' => 'route', 'label' => 'Ruta' ],
            [ 'index' => 'order', 'label' => 'Orden' ],
            [ 'index' => 'state', 'label' => 'Estado'],
            [ 'index' => 'parent.label', 'label' => 'Padre'],
            [ 'index' => 'type', 'label' => 'Tipo' ],
            [ 'index' => 'permission_name', 'label' => 'Permiso' ],
            [ 'index' => 'actions', 'label' => '', 'width' => '100px']
        ];

        $rows = Page::filterAdvance($headers,[
            'search' => $this->search,
            'sort' => [
                'field' => $this->sortBy, 
                'direction' => $this->sortDirection
            ],
            'filters' => $this->processFilters(),
        ])->paginate($this->per_page ?? 10);

        $pages = Page::where('state',true)->orderBy('label')->get(['id','label']);
        $permissions = Permission::where('module','menu')->orderBy('name')->get('name');

        return view('livewire.admin.pages',compact('headers','rows','pages','permissions'));
    }

    public function store() {
        $this->validate([
            'page.label' => 'required|string|max:255',
            'page.icon' => 'nullable|string|max:255',
            'page.route' => 'nullable|string|max:255',
            'page.order' => 'nullable|integer',
            'page.type' => 'required|in:header,parent,page',
            'page.page_id' => 'required_if:page.type,page|nullable|exists:pages,id',
            'page.permission_name' => 'nullable|string|exists:permissions,name',
        ]);

        try {
            Page::create($this->page);
    
            $this->toastSuccess('Página creada con éxito.');
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al crear la página: ' . $th->getMessage());
        }

    }

    public function edit($id) {
        $page = Page::findOrFail($id);
        $this->page = $page->toArray();
        Flux::modal('editPage')->show();
    }

    public function update() {
        $this->validate([
            'page.label' => 'required|string|max:255',
            'page.icon' => 'nullable|string|max:255',
            'page.route' => 'nullable|string|max:255',
            'page.order' => 'nullable|integer',
            'page.type' => 'required|in:header,parent,page',
            'page.page_id' => 'required_if:page.type,page|nullable|exists:pages,id',
            'page.permission_name' => 'nullable|string|exists:permissions,name',
            'page.state' => 'required|boolean',
        ]);

        try {
            
            $page = Page::findOrFail($this->page['id']);
            $page->fill($this->page);
            if($page->isDirty()) {
                $page->update($this->page);
                $this->toastSuccess('Página actualizada con éxito.');
            }
    
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al actualizar la página');
        }

    }

    public function disableItem(int $id) {
        $this->page = Page::findOrFail($id)->toArray();
        Flux::modal('disableItem')->show();
    }

    public function disabled() {

        try {
            $page = Page::findOrFail($this->page['id']);
            $page->state = false;
            $page->save();

            $this->toastSuccess('Página desactivada con éxito.');
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al desactivar la página');
        }
    }

    public function deleteItem(int $id) {
        $this->page = Page::findOrFail($id)->toArray();
        Flux::modal('deleteItem')->show();
    }

    public function destroy() {
        try {
            $page = Page::findOrFail($this->page['id']);
            $page->delete();
    
            $this->toastSuccess('Página eliminada con éxito.');
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al eliminar la página');
        }
    }

    public function resetData () {
        $this->reset('page');
        $this->resetValidation();
        Flux::modals()->close();
    }

}

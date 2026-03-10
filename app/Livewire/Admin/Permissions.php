<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\DataTableTrait;
use App\Livewire\Traits\Interact;
use App\Models\Permission;
use Flux\Flux;
use Livewire\Component;

class Permissions extends Component
{
    use DataTableTrait, Interact;

    public array $permission = [];

    public function mount() {
        $this->initializeDataTable();
        $this->sortBy = 'module';
    }

    public function render() {
        $headers = [
            [ 'index' => 'id', 'label' => '#', 'align' => 'center' ],
            [ 'index' => 'name', 'label' => 'Permiso' ],
            [ 'index' => 'module', 'label' => 'Módulo' ],
            [ 'index' => 'actions', 'label' => '', 'width' => '100px' ]
        ];

        $rows = Permission::filterAdvance($headers,[
            'search' => $this->search,
            'sort' => [
                'field' => $this->sortBy, 
                'direction' => $this->sortDirection
            ],
            'filters' => $this->processFilters(),
        ])->paginate($this->per_page);

        return view('livewire.admin.permissions', compact('headers', 'rows'));
    }

    public function store() {
        $this->validate([
            'permission.name' => 'required|string|max:255',
            'permission.module' => 'required|string|max:255',
        ]);

        try {
            Permission::create([
                'name' => trim($this->permission['name']),
                'module' => $this->permission['module'],
                'guard_name' => 'web',
            ]);
    
            $this->toastSuccess('Permiso creado exitosamente.');
    
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al crear el permiso');
        }

    }

    public function edit(int $id) {
        $this->permission = Permission::findOrFail($id)->toArray();
        Flux::modal('editPermission')->show();
    }

    public function update() {
        $this->validate([
            'permission.name' => 'required|string|min:3|unique:permissions,name,' . $this->permission['id'],
            'permission.module' => 'required|string|max:255',
        ]);

        try {
            
            $permission = Permission::find($this->permission['id']);
            $permission->name = trim($this->permission['name']);
            $permission->module = $this->permission['module'];
            
            if($permission->isDirty()) {
                $permission->save();
                $this->toastSuccess('Permiso actualizado exitosamente.');
            }
    
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al actualizar el permiso');
        }

    }

    public function delete(int $id) {
       $this->permission = Permission::findOrFail($id)->toArray();
        Flux::modal('deletePermission')->show();
    }

    public function destroy() {
        try {
            $permission = Permission::find($this->permission['id']);
            $permission->delete();
    
            $this->toastSuccess('Permiso eliminado exitosamente.');
    
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al eliminar el permiso');
        }
    }

    public function resetData() {
        $this->reset('permission');
        $this->resetValidation();
        Flux::modals()->close();
    }
}

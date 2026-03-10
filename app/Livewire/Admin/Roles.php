<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\DataTableTrait;
use App\Livewire\Traits\Interact;
use App\Models\Permission;
use Flux\Flux;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    use DataTableTrait, Interact;

    public array $role = [
        'name' => null,
        'permissions' => []
    ];
    
    public ?string $search_permissions = null;

    public function mount() {
        $this->initializeDataTable();
    }

    public function render() {
        $headers = [
            [ 'index' => 'id', 'label' => '#', 'align' => 'center' ],
            [ 'index' => 'name', 'label' => 'Role' ],
            [ 'index' => 'actions', 'label' => '', 'width' => '100px' ]
        ];

        $rows = Role::when($this->search,function($query){
                $query->where('name','like','%'.$this->search.'%')
                    ->orWhere('id',$this->search);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->per_page ?? 10);

        $all_permissions = Permission::where('name','like','%'.$this->search_permissions.'%')
            ->get()
            ->groupBy('module');

        return view('livewire.admin.roles', compact('headers', 'rows','all_permissions'));
    }

    public function store() {

        $this->validate([
            'role.name' => 'required|string|max:255',
        ]);

        try {
            
            $role = Role::create([
                'name' => $this->role['name'],
            ]);
    
            if(!empty($this->role['permissions'])) {
                $role->permissions()->sync($this->role['permissions'] ?? []);
            }
    
            $this->toastSuccess('Role creado exitosamente.');
    
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al crear el role');
        }


    }

    public function edit(int $id) {
        $role = Role::findOrFail($id);
        $this->role = $role->toArray();
        $this->role['permissions'] = $role->permissions->pluck('id')->toArray();
        Flux::modal('editRole')->show();
    }

    public function update() {

        $this->validate([
            'role.name' => 'required|string|max:255',
        ]);

        try {
            $role = Role::findOrFail($this->role['id']);
    
            $role->name = $this->role['name'];
    
            if($role->isDirty('name') || hasChanged($this->role['permissions'], $role->permissions->pluck('id')->toArray())) {
                $role->save();
                $role->permissions()->sync($this->role['permissions'] ?? []);
                $this->toastSuccess('Role actualizado exitosamente.');
            }

            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al actualizar el role');
        }


    }

    public function delete(int $id) {
        $this->role = Role::findOrFail($id)->toArray();
        Flux::modal('deleteRole')->show();
    }

    public function destroy () {
        try {
            
            $role = Role::findOrFail($this->role['id']);
            $role->delete();
    
            $this->toastSuccess('Role eliminado exitosamente.');
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Error al eliminar el role');
        }
    }

    public function resetData() {
        $this->reset('role');
        $this->resetValidation();
        Flux::modals()->close();
    }
}

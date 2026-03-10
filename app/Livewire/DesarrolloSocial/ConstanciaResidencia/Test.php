<?php

namespace App\Livewire\DesarrolloSocial\ConstanciaResidencia;

use App\Livewire\Traits\DataTableTrait;
use App\Models\Permission;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class Test extends Component
{

    use DataTableTrait;

    public function mount() {
        $this->initializeDataTable();
    }

    public function render() {
        
        $headers = [
            [ 'index' => 'id', 'label' => '#' ],
            [ 'index' => 'name', 'label' => 'Nombre' ],
            [ 'index' => 'module', 'label' => 'Módulo' ],
            [ 'index' => 'actions', 'label' => '', 'width' => '100px' ],
        ];

        $rows = Permission::filterAdvance($headers, [
                'search' => $this->search,
                'sort' => [
                    'field' => $this->sortBy, 
                    'direction' => $this->sortDirection
                ],
                'filters' => $this->processFilters(),
            ])->paginate($this->per_page ?? 10);

        return view('livewire.desarrollo-social.constancia-residencia.test', compact('headers', 'rows') );
    }

    public function openModal() {
         Flux::modal('edit-profile')->show();
    }
}

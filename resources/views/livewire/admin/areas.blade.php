<section class="w-full">

    @can('areas.store')
        <div class="flex justify-center mb-4">
            <flux:modal.trigger name="newArea">
                <flux:button 
                    icon="plus" 
                    title="Crear nueva área"
                    variant="primary" >
                    Crear nueva área
                </flux:button>
            </flux:modal.trigger>
        </div>
    @endcan
    
    @can('areas.list')
        <x-data-table :$headers :$rows>        
            @interact('active',$row)
                <flux:icon 
                    :name="$row->active ? 'check-circle' : 'x-circle'"
                    class="size-5"
                    :class="$row->active ? 'text-green-500' : 'text-red-500'" />
            @endinteract

            @interact('actions', $row)
                <flux:dropdown >
                    <flux:button 
                        size="sm" 
                        icon="ellipsis-vertical" 
                        class="cursor-pointer" 
                        variant="ghost" 
                    />
                    <flux:menu>
                        @can('areas.edit')
                            <flux:menu.item 
                                icon="pencil-square" 
                                wire:click="edit({{ $row->id }})" >
                                Editar
                            </flux:menu.item>
                        @endcan
                        
                        @can('areas.disabled')
                            @if ($row->active)
                                <flux:menu.item 
                                    variant="danger" 
                                    icon="x-circle"
                                    wire:click="disableItem({{ $row->id }})" >
                                    Desactivar
                                </flux:menu.item>
                            @endif
                        @endcan

                        @can('areas.delete')
                            <flux:menu.item  
                                icon="trash"
                                variant="danger"
                                wire:click="delete({{ $row->id }})" >
                                Eliminar
                            </flux:menu.item>
                        @endcan
                    </flux:menu>
                </flux:dropdown>
            @endinteract
        </x-data-table>
    @endcan

    <flux:modal name="newArea" class="min-w-[22rem]" flyout @close="resetData">
        <form wire:submit.prevent="store" class="space-y-6">
            <flux:heading size="lg">Nueva área</flux:heading>
            <div class="grid gap-4">
                <flux:input 
                    label="Nombre *"
                    wire:model="area.name"
                    icon="pencil-square" 
                    placeholder="Nombre de la área"
                    autofocus
                    required
                />

                <flux:select 
                    label="Pertenece a" 
                    wire:model="area.area_id" >

                    <flux:select.option 
                        value="">
                        -- Seleccione dependencia --
                    </flux:select.option>
                    @forelse ($dependencies as $dependency)
                        <flux:select.option 
                            value="{{ $dependency->id }}">
                            {{ $dependency->id." - ".$dependency->name }}
                        </flux:select.option>
                        
                    @empty
                        <flux:select.option>
                            No hay datos
                        </flux:select.option>
                    @endforelse
                </flux:select>

            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button icon="x-mark" variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" icon="arrow-up-on-square-stack" variant="danger">Crear</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="editArea" class="min-w-[22rem]" flyout @close="resetData">
        <form wire:submit.prevent="update" class="space-y-6">
            <flux:heading size="lg">Editar área</flux:heading>

            @if(isset($this->area['active']) && !$this->area['active'])
                <flux:switch 
                    wire:model="area.active"
                    label="Habilitar área"
                    align="left" 
                />
            @endif

            <div class="grid gap-4">
                <flux:input 
                    label="Nombre *"
                    icon="pencil-square"
                    wire:model="area.name" 
                    placeholder="Nombre de la área"
                    autofocus
                    required
                />

                <flux:select 
                    label="Pertenece a" 
                    wire:model="area.area_id" >

                    <flux:select.option 
                        value="">
                        -- Seleccione dependencia --
                    </flux:select.option>
                    @forelse ($dependencies as $dependency)
                        <flux:select.option 
                            value="{{ $dependency->id }}">
                            {{ $dependency->id." - ".$dependency->name }}
                        </flux:select.option>
                        
                    @empty
                        <flux:select.option>
                            No hay datos
                        </flux:select.option>
                    @endforelse
                </flux:select>

            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button icon="x-mark" variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" icon="arrow-path" variant="danger">Actualizar</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="deleteArea" class="min-w-[22rem]" @close="resetData">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar área?</flux:heading>

                <flux:text class="mt-2">
                    Esta seguro de eliminar el área.<br>
                    <strong>{{ $this->area['name'] ?? null }}</strong>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button icon="x-mark" variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button wire:click="destroy" icon="trash" variant="danger">Sí, eliminar</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="disableArea" class="min-w-[22rem]" @close="resetData">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Desactivar área?</flux:heading>

                <flux:text class="mt-2">
                    Esta seguro de desactivar el área.<br>
                    <strong>{{ $this->area['name'] ?? null }}</strong>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button icon="x-mark" variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button wire:click="disabled" icon="x-circle" variant="danger">Sí, desactivar</flux:button>
            </div>
        </div>
    </flux:modal>

</section>
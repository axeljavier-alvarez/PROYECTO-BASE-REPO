<section class="w-full">

    @can('pasos-pedales.sedes.store')
        <div class="flex justify-center mb-4">
            <flux:modal.trigger name="newSede">
                <flux:button 
                    icon="plus" 
                    title="Crear nueva sede" 
                    variant="primary" >
                    Crear nueva sede
                </flux:button>
            </flux:modal.trigger>
        </div>
    @endcan

    @can('pasos-pedales.sedes.list')
        <x-data-table :$headers :$rows >
            @interact('actions', $row)
                <flux:dropdown >
                    <flux:button 
                        size="sm" 
                        icon="ellipsis-vertical" 
                        class="cursor-pointer" 
                        variant="ghost" 
                    />
                    <flux:menu>
                        @can('pasos-pedales.sedes.edit')
                            <flux:menu.item 
                                icon="pencil-square" 
                                wire:click="edit({{ $row->id }})" >
                                Editar
                            </flux:menu.item>
                        @endcan
                        @can('pasos-pedales.sedes.delete')
                            <flux:menu.item 
                                icon="trash"
                                variant="danger" 
                                wire:click="delete({{ $row->id }})" >
                                Eliminar
                            </flux:menu.item>
                        @endcan
                        @can('pasos-pedales.sedes.ver-areas')                            
                            <flux:menu.item 
                                icon="eye" 
                                wire:click="previewArea({{ $row->id }})" >
                                Ver áreas
                            </flux:menu.item>
                        @endcan
                    </flux:menu>
                </flux:dropdown>
            @endinteract
        </x-data-table>
    @endcan

    <flux:modal name="newSede" class="min-w-[22rem]" flyout @close="resetData">
        <form wire:submit.prevent="store" class="space-y-6">
            <flux:heading size="lg">Crear nueva sede</flux:heading>
            
            <flux:spacer />

            <div class="grid gap-4">
                <flux:input 
                    icon="building-2"
                    label="Nombre *"
                    wire:model="sede.nombre"
                    required
                />
                <flux:textarea 
                    icon="document-text"
                    label="Descripcion"
                    wire:model="sede.descripcion"
                />

                <fieldset class="p-8 border rounded-lg">
                    <legend class="px-2">Agregar áreas a la sede</legend>
                    @foreach ($this->sede['areas'] as $index => $sede)
                        <div class="flex gap-4 mb-2">
                            <flux:input
                                icon="pencil-square"
                                label="Nombre del área *"
                                wire:model="sede.areas.{{ $index }}.nombre"
                                placeholder="Escriba aquí el nombre de la área"
                                
                            />
                            <flux:input 
                                type="file" 
                                wire:model="sede.areas.{{ $index }}.path_imagen" 
                                label="Imagen del área"
                                accept="image/*"
                            />
                            
                            <div class="grid">
                                @if(!$loop->first || count($this->sede['areas']) > 1 )
                                <flux:button
                                    icon="x-mark"
                                    variant="danger"
                                    size="xs"
                                    wire:click="deleteArea({{ $loop->index }})"
                                />
                                @endif
                                @if($loop->last)
                                    <flux:button
                                        icon="plus"
                                        variant="filled"
                                        size="xs"
                                        wire:click="addArea"
                                    />
                                @endif
                            </div>
                        </div>
                    @endforeach
                </fieldset>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button 
                        icon="x-mark" 
                        variant="ghost">
                        Cancelar
                    </flux:button>
                </flux:modal.close>
                <flux:button 
                    type="submit" 
                    icon="arrow-up-on-square-stack" 
                    variant="danger">
                    Crear
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="editSede" class="min-w-[22rem]" flyout @close="resetData">
        <form wire:submit.prevent="update" class="space-y-6">
            <flux:heading size="lg">Editar sede</flux:heading>
            
            <flux:spacer />

            <div class="grid gap-4">
                <flux:input 
                    icon="building-2"
                    label="Nombre *"
                    wire:model="sede.nombre"
                    required
                />
                <flux:textarea 
                    icon="document-text"
                    label="Descripcion"
                    wire:model="sede.descripcion"
                />

                <fieldset class="p-8 border rounded-lg">
                    <legend class="px-2">Agregar áreas a la sede</legend>
                    @foreach ($this->sede['areas'] as $index => $sede)
                        <div class="flex gap-4 mb-2">
                            <flux:input
                                icon="pencil-square"
                                label="Nombre del área *"
                                wire:model="sede.areas.{{ $index }}.nombre"
                                placeholder="Escriba aquí el nombre de la área"
                                
                            />
                            <flux:input 
                                type="file" 
                                wire:model="sede.areas.{{ $index }}.path_imagen" 
                                label="Imagen del área"
                                accept="image/*"
                            />
                            
                            <div class="grid">
                                @if(!$loop->first || count($this->sede['areas']) > 1 )
                                <flux:button
                                    icon="x-mark"
                                    variant="danger"
                                    size="xs"
                                    wire:click="deleteArea({{ $loop->index }})"
                                />
                                @endif
                                @if($loop->last)
                                    <flux:button
                                        icon="plus"
                                        variant="filled"
                                        size="xs"
                                        wire:click="addArea"
                                    />
                                @endif
                            </div>
                        </div>
                    @endforeach
                </fieldset>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button 
                        icon="x-mark" 
                        variant="ghost">
                        Cancelar
                    </flux:button>
                </flux:modal.close>
                <flux:button 
                    type="submit" 
                    icon="arrow-path" 
                    variant="danger">
                    Actualizar
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="viewAreas" class="min-w-[22rem] max-w-2xl" flyout @close="resetData">
        
        <flux:heading size="lg">Ver areas</flux:heading>
        
        <flux:spacer />

        <div>
            <ul class="grid grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse ($this->sede['areas'] as $area)
                    <li wire:click="viewImagenArea('{{ $area['url_imagen'] ?? null }}')"
                        class="flex items-center gap-2 text-xs cursor-pointer hover:bg-zinc-300 hover:dark:bg-zinc-600 px-2 py-2 rounded-lg">
                        <flux:icon.photo class="size-5" />
                        <span>{{ $area['nombre'] }}</span> 
                    </li>
                @empty
                    <li>No hay áreas registradas</li>
                @endforelse
            </ul>
            <br>
            <img 
                class="w-full h-auto rounded-lg border-2 border-gray-500 object-cover" 
                src="{{ $this->urlImagenArea }}"
            />
        </div>

        <div class="flex gap-2">
            <flux:spacer />

            <flux:modal.close>
                <flux:button 
                    icon="x-mark" 
                    variant="ghost">
                    Cancelar
                </flux:button>
            </flux:modal.close>
        </div>
    </flux:modal>

    <flux:modal name="deleteSede" class="min-w-[22rem]" @close="resetData">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar sede?</flux:heading>

                <flux:text class="mt-2">
                    Esta seguro de eliminar la sede y sus áreas.<br>
                    !! Esta acción no se puede deshacer. ¡¡
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

</section>

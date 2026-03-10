<section class="w-full">
    <x-data-table :$headers :$rows >
        @interact('solicitud.primer_nombre',$row)
            <div class="grid">
                <span class="flex gap-1 items-center text-lg">
                    <flux:icon name="user" class="size-4" />
                    {{ $row->solicitud?->nombre_completo }}
                </span>
                <span class="flex gap-1 items-center text-sm">
                    <flux:icon name="envelope" class="size-4" />
                    {{ $row->solicitud?->correo }}
                </span>
                <span class="flex gap-1 items-center text-sm">
                    <flux:icon name="phone" class="size-4" />
                    {{ $row?->solicitud?->telefono }}
                </span>
            </div>
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
                    @can('pasos-pedales.autorizacion-solicitud.review')
                    <flux:menu.item 
                        icon="eye" 
                        wire:click="viewRequest({{ $row->id }})" >
                        Revisar
                    </flux:menu.item>
                    @endcan
                </flux:menu>
            </flux:dropdown>
        @endinteract
    </x-data-table>

    <flux:modal name="revision-solicitud" class="min-w-[22rem]" @close="resetData">
        <form wire:submit.prevent="store" class="space-y-6">
            <flux:heading size="lg">Revisión de solicitud</flux:heading>
            
            <x-navbar 
                :items="$this->navItems"
            />

            <flux:spacer />

            <div>
                @if($this->nav_option == 1)
                    <fieldset class="border-2 p-4 rounded-lg dark:border-gray-500">
                        <legend class="px-3">DATOS DEL SOLICITANTE</legend>
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div class="lg:col-span-2">
                                <flux:input 
                                    wire:model="expediente.solicitud.nombre_completo" 
                                    label="Nombre completo" 
                                    icon="user" 
                                    readonly 
                                    disabled
                                />
                            </div>
                            <flux:input 
                                wire:model="expediente.solicitud.cui" 
                                label="Cui" 
                                icon="id-card" 
                                readonly 
                                disabled 
                            />
                            <flux:input 
                                wire:model="expediente.solicitud.nit" 
                                label="Nit" 
                                icon="id-card" 
                                readonly 
                                disabled 
                            />
                            <flux:input 
                                wire:model="expediente.solicitud.correo" 
                                label="Correo" 
                                icon="envelope" 
                                readonly 
                                disabled 
                            />
                            <flux:input 
                                wire:model="expediente.solicitud.telefono" 
                                label="Teléfono" 
                                icon="phone" 
                                readonly 
                                disabled 
                            />
                            <flux:input 
                                wire:model="expediente.solicitud.tipo_persona.nombre" 
                                label="Tipo persona" 
                                icon="users" 
                                readonly 
                                disabled 
                            />
                            <flux:input 
                                wire:model="expediente.solicitud.patente_comercio" 
                                label="Patente de comercio" 
                                icon="id-card" 
                                readonly 
                                disabled 
                            />
                            <flux:input 
                                wire:model="expediente.solicitud.zona_id" 
                                label="Zona" 
                                icon="signpost" 
                                readonly 
                                disabled 
                            />
                            <flux:input 
                                wire:model="expediente.solicitud.colonia" 
                                label="Colonia" 
                                icon="map-pinned" 
                                readonly 
                                disabled 
                            />
                            <div class="lg:col-span-2">
                                <flux:input wire:model="expediente.solicitud.domicilio" 
                                label="Domicilio" 
                                icon="map-pin-house" 
                                readonly 
                                disabled 
                            />
                            </div>
                            <div class="lg:col-span-2">
                                <flux:textarea 
                                    wire:model="expediente.solicitud.actividad_negocio" 
                                    label="Actividad de negocio" 
                                    rows="4" 
                                    readonly 
                                    disabled 
                                />
                            </div>
                        </div>
                    </fieldset>
                @endif

                @if($this->nav_option == 2)   
                    <fieldset class="border-2 p-4 rounded-lg dark:border-gray-500">
                        <legend class="px-3">ESPACIO SOLICITADO</legend>
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div class="lg:col-span-2">
                                <flux:input 
                                    wire:model="expediente.solicitud.sede.nombre" 
                                    label="Sede" 
                                    icon="map-pinned" 
                                    readonly 
                                    disabled 
                                />
                            </div>
                            <flux:input 
                                wire:model="expediente.solicitud.ancho" 
                                label="Ancho (mts)" 
                                icon="ruler-dimension-line" 
                                readonly 
                                disabled 
                            />
                            <flux:input 
                                wire:model="expediente.solicitud.largo" 
                                label="Largo (mts)" 
                                icon="ruler-dimension-line" 
                                readonly 
                                disabled 
                            />
                            <div class="lg:col-span-2">
                                <flux:textarea 
                                    wire:model="expediente.solicitud.observaciones" 
                                    label="Observaciones" 
                                    rows="4" 
                                    readonly 
                                    disabled 
                                />
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border-2 p-4 rounded-lg dark:border-gray-500">
                        <legend class="px-3">ESPACIOS ASIGNADO</legend>
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div class="lg:col-span-2">
                                <flux:input 
                                    wire:model="expediente.area_sede.sede.nombre" 
                                    label="Sede" 
                                    icon="map-pinned" 
                                    readonly 
                                    disabled 
                                />
                            </div>
                            <div class="lg:col-span-2">
                                <flux:input 
                                    wire:model="expediente.area_sede.nombre" 
                                    label="Area asignada" 
                                    icon="map" 
                                    readonly 
                                    disabled 
                                />
                            </div>
                            <img 
                                class="lg:col-span-2 h-auto rounded-lg border-2 border-gray-500 object-cover" 
                                src="{{ $this->expediente['area_sede']['url_imagen'] ?? '' }}"
                            />
                            <div class="lg:col-span-2">
                                <flux:textarea 
                                    wire:model="expediente.descripcion" 
                                    label="Descripción" 
                                    rows="4" 
                                    readonly 
                                    disabled 
                                />
                            </div>
                        </div>
                        
                    </fieldset>
                @endif

                @if($this->nav_option == 3)
                    <fieldset class="border-2 py-4 lg:px-2 rounded-lg dark:border-gray-500 lg:flex gap-4">
                        <legend class="px-3">DOCUMENTOS</legend>
                        @if($this->expediente['solicitud']['documentos'])
                        <ul>
                            @foreach ($this->expediente['solicitud']['documentos'] as $doc)                            
                            <li wire:click="previewDoc('{{ $doc['url'] }}')" 
                                class="flex items-center gap-2 text-xs cursor-pointer hover:bg-zinc-300 hover:dark:bg-zinc-600 px-2 py-2 rounded-lg {{ $this->urlDoc == $doc['url'] ? 'bg-zinc-600' : '' }}">
        
                                <flux:icon name="document" class=" size-5" />
                                <span>{{ $doc['nombre'] }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                        <embed 
                            class="w-full lg:flex-1 h-[37rem] rounded-lg border-2 border-gray-500" 
                            src="{{ $this->urlDoc }}" 
                            type="application/pdf" 
                        />
                    </fieldset>
                @endif

                @if($this->nav_option == 4)
                    <div>
                        <flux:textarea wire:model="expediente.latestWorkflow.observacion" maxlength="1000" label="Observación" rows="6"/>
                        <br>
                        <div class="flex justify-evenly gap-2">
                            <flux:button wire:click="authorizedRequest" icon="check" variant="primary" color="sky">Autorizar solicitud</flux:button>
                            <flux:button wire:click="rejectAssign" icon="x-mark" variant="primary" color="orange">Rechazar Asignación</flux:button>
                        </div>
                        <br>
                        <div class="flex justify-evenly">
                            <flux:button wire:click="rejectRequest" icon="x-mark" variant="primary" color="red">Rechazar solicitud</flux:button>
                        </div>
                        
                    </div>
                @endif
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button icon="x-mark" variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
            </div>
        </form>
    </flux:modal>

</section>

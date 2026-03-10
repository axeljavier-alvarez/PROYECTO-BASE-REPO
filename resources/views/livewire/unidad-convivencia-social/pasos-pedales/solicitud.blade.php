<section class="w-full">

    <x-stepper :items="$this->steps">
        @if($this->step == 1)
            <article>
                <div class="grid xl:grid-cols-2 gap-4">
                    <flux:input 
                        label="Primer nombre *"
                        wire:model="nueva_solicitud.primer_nombre"
                        icon="pencil-square"
                        required
                        autofocus
                    />
                    <flux:input 
                        label="Segundo nombre"
                        icon="pencil-square"
                        wire:model="nueva_solicitud.segundo_nombre"
                    />
                    <flux:input 
                        label="Primer apellido *"
                        icon="pencil-square"
                        wire:model="nueva_solicitud.primer_apellido"
                        required
                    />
                    <flux:input 
                        label="Segundo apellido"
                        icon="pencil-square"
                        wire:model="nueva_solicitud.segundo_apellido"
                    />
                    <flux:input 
                        label="Cui *"
                        icon="identification"
                        wire:model="nueva_solicitud.cui"
                        maxlength="13"
                        required
                    />
                    <flux:input 
                        label="Nit *"
                        icon="identification"
                        wire:model="nueva_solicitud.nit"
                        type="number"
                        required
                    />
                    <flux:input 
                        label="Correo *"
                        icon="envelope"
                        wire:model="nueva_solicitud.correo"
                        type="email"
                        required
                    />
                    <flux:input 
                        label="Telefono *"
                        icon="phone"
                        wire:model="nueva_solicitud.telefono"
                        type="tel"
                        required
                    />
                            
                    <flux:select 
                        label="Tipo de persona *" 
                        wire:model="nueva_solicitud.tipo_persona"
                        required >
        
                        <flux:select.option> -- Seleccione una opción -- </flux:select.option> 
        
                        @forelse ($tipo_personas as $tipo_persona)
                            <flux:select.option 
                                value="{{ $tipo_persona }}">
                                {{ $tipo_persona }}
                            </flux:select.option>                        
                        @empty                    
                            <flux:select.option>No hay data</flux:select.option>                        
                        @endforelse
                    </flux:select>
        
                    <flux:input 
                        label="Patente de comercio *"
                        icon="document-text"
                        wire:model="nueva_solicitud.patente_comercio"
                        maxlength="20"
                        required
                    />
        
                    <flux:select 
                        label="Zonas" 
                        wire:model="nueva_solicitud.zona_id">
        
                        <flux:select.option> -- Seleccione una zona -- </flux:select.option> 
        
                        @forelse ($zonas as $zona)
                            <flux:select.option 
                                value="{{ $zona->id }}">
                                {{ $zona->nombre }}
                            </flux:select.option>                        
                        @empty                    
                            <flux:select.option>No hay data</flux:select.option>                        
                        @endforelse
                    </flux:select>
        
                    <flux:input 
                        label="Colonia"
                        icon="signpost"
                        wire:model="nueva_solicitud.colonia"
                    />
        
                    <div class="col-span-2">
                        <flux:input 
                            label="Domicilio *"
                            icon="map-pin-house" 
                            wire:model="nueva_solicitud.domicilio"
                            required
                        />
                    </div>
        
                    <div class="col-span-2">
                        <flux:textarea 
                            label="Actividad de negocio *" 
                            wire:model="nueva_solicitud.actividad_negocio"
                            required
                        />
                    </div>
                    
                </div>
            </article>
        @endif
        
        @if($this->step == 2)
            <article>
                <div class="grid xl:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <flux:select 
                            label="Sedes disponibles *" 
                            wire:model="nueva_solicitud.sede_id"
                            required >
            
                            <flux:select.option> -- Seleccione una sede -- </flux:select.option> 
    
                            @forelse ($sedes as $sede)
                                <flux:select.option 
                                    value="{{ $sede->id }}">
                                    {{ $sede->nombre }}
                                </flux:select.option>                        
                            @empty                    
                                <flux:select.option>No hay data</flux:select.option>                        
                            @endforelse
                        </flux:select>
                    </div>
                    <flux:input 
                        label="Largo(mts) *"
                        wire:model="nueva_solicitud.largo"
                        type="number"
                        icon="ruler-dimension-line"
                        step="0.01"
                        required
                    />
                    <flux:input 
                        label="Ancho(mts) *"
                        wire:model="nueva_solicitud.ancho"
                        type="number"
                        icon="ruler-dimension-line"
                        step="0.01"
                        required
                    />
                    <div class="col-span-2">
                        <flux:textarea 
                            label="Observaciones"
                            wire:model="nueva_solicitud.observaciones"
                        />
                    </div>
                </div>
            </article>
        @endif
    
        @if($this->step == 3)
            <article class="flex justify-center  mb-4">
                <div class="grid xl:grid-cols-3 gap-12">
                    <x-card-upload
                        class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0"
                        label="Carta de solicitud"
                        wire:model="nueva_solicitud.documentos.carta_solicitud"
                        accept="application/pdf"
                        description="pdf (max 5 mb)"
                        required
                    />
                    <x-card-upload
                        class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0"
                        label="Dpi"
                        wire:model="nueva_solicitud.documentos.dpi"
                        accept="application/pdf"
                        description="pdf (max 5 mb)"
                        required
                    />
                    <x-card-upload
                        class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0"
                        label="Rtu"
                        wire:model="nueva_solicitud.documentos.rtu"
                        accept="application/pdf"
                        description="pdf (max 5 mb)"
                        required
                    />
                    <x-card-upload
                        class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0"
                        label="Recibo de servicio"
                        wire:model="nueva_solicitud.documentos.recibo_servicios"
                        accept="application/pdf"
                        description="pdf (max 5 mb)"
                        required
                    />
                    <x-card-upload
                        class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0"
                        label="Patente de comercio"
                        wire:model="nueva_solicitud.documentos.patente_comercio"
                        accept="application/pdf"
                        description="pdf (max 5 mb)"
                        required
                    />
                    @if(isset($this->nueva_solicitud['tipo_persona_id']) && $this->nueva_solicitud['tipo_persona_id'] == 2)
                        <x-card-upload
                            class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0"
                            label="Acta Notarial"
                            wire:model="nueva_solicitud.documentos.acta_notarial"
                            accept="application/pdf"
                            description="pdf (max 5 mb)"
                            required
                        />
                    @endif
                </div>
    
            </article>  
        @endif
    </x-stepper>

</section>

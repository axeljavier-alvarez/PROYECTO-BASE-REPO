<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>

    <x-settings.layout>
        <form wire:submit.prevent="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="grid xl:grid-cols-2 gap-4">

                <div class="col-span-2">
                    <flux:input 
                        wire:model="information.cui" 
                        icon="identification"
                        :label="__('Dpi')" 
                        readonly 
                    />
                </div>
                <flux:input 
                    wire:model="information.nombres" 
                    icon="pencil-square"
                    :label="__('Nombres')" 
                    type="text" 
                    required 
                    autofocus 
                />
                <flux:input 
                    wire:model="information.apellidos" 
                    icon="pencil-square"
                    :label="__('Apellidos')" 
                    type="text" 
                    required 
                />
                <flux:input 
                    wire:model="information.fecha_nacimiento" 
                    icon="calendar"
                    :label="__('Fecha de nacimiento')" 
                    type="date"
                    required 
                />
                <flux:radio.group 
                    wire:model="information.sexo" 
                    label="Seleccione sexo">
                    <flux:radio 
                        value="F" 
                        label="Femenino" 
                    />
                    <flux:radio 
                        value="M" 
                        label="Masculino" 
                    />
                </flux:radio.group>
                <flux:input 
                    wire:model="information.telefono"
                    icon="phone" 
                    :label="__('Phone')" 
                    type="tel"
                    maxlength="8"
                    placeholder="55555555" 
                    mask="99999999" 
                    required 
                />
                <flux:input 
                    wire:model="information.correo"
                    icon="envelope"
                    :label="__('Email')" 
                    type="email" 
                    required 
                />

                <flux:select 
                    label="Departamentos" 
                    wire:model.live="departamento_id"
                    placeholder="Seleccione departamento">
                    @forelse ($departamentos as $departamento)
                        <flux:select.option value="{{ $departamento->id }}">{{ $departamento->nombre }}
                        </flux:select.option>
                    @empty
                        <flux:select.option>No hay data</flux:select.option>
                    @endforelse
                </flux:select>

                <flux:select 
                    label="Municipios" 
                    wire:model="domicilio.municipio_id" 
                    placeholder="Seleccione municipio">
                    @forelse ($municipios as $municipio)
                        <flux:select.option value="{{ $municipio->id }}">{{ $municipio->nombre }}</flux:select.option>
                    @empty
                        <flux:select.option>No hay data</flux:select.option>
                    @endforelse
                </flux:select>

                <flux:select 
                    label="Zonas" 
                    wire:model="domicilio.zona_id" 
                    placeholder="Seleccione zona">
                    @forelse ($zonas as $zona)
                        <flux:select.option value="{{ $zona->id }}">{{ $zona->nombre }}</flux:select.option>
                    @empty
                        <flux:select.option>No hay data</flux:select.option>
                    @endforelse
                </flux:select>

                <flux:input 
                    wire:model="domicilio.colonia"
                    icon="landmark"
                    :label="__('Colonia')" 
                    required 
                />
                <div class="col-span-2">
                    <flux:input 
                        wire:model="domicilio.direccion"
                        icon="map-pinned"
                        :label="__('Dirección')" 
                        required 
                    />
                </div>

                <flux:input 
                    :label="__('Area')"
                    icon="building-2"
                    value="{{ $user->area->name }}" 
                    type="text" 
                    readonly 
                />
                <flux:input 
                    :label="__('Role')"
                    icon="tag"
                    value="{{ $user->role_name }}" 
                    type="text" 
                    readonly 
                />
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button 
                        variant="primary" 
                        type="submit" 
                        class="w-full">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message 
                    class="me-3" 
                    on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
        @can('Sysadmin')
            @if ($this->showDeleteUser)
                <livewire:settings.delete-user-form />
            @endif
        @endcan
    </x-settings.layout>
</section>

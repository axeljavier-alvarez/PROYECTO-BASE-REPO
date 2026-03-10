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
                    <flux:menu.item 
                        icon="eye" 
                        wire:click="viewTimeLineRequest({{ $row->id }})" >
                        Ver
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        @endinteract
    </x-data-table>

    <flux:modal name="time-line" class="min-w-[22rem]" @close="resetData">
        <div class="space-y-6">
            <flux:heading size="lg">Linea de tiempo de solicitud</flux:heading>
            
            <div class="p-4 h-[30rem] overflow-auto">
                <ol class="border-l-2 relative">
                    @if (isset($this->expediente->workflows))
                        @forelse ($this->expediente->workflows as $workflow)                        
                        <li class="-ml-4 mt-5">
                            <div class="flex gap-4">
                                <span class="size-8 bg-blue-500 rounded-full flex items-center justify-center absolute">
                                    <flux:icon name="calendar-days" class="size-5 text-white"/>
                                </span>
                                <div class="grid pl-12 text-zinc-500">
                                    <span class="text-xl font-bold">{{ $workflow->estado->nombre }}</span>
                                    @if($workflow->user)
                                        <span class="text-xs flex items-center gap-2">
                                            <flux:icon.user-circle class="size-5" />
                                            {{ 'Atendió : ' .$workflow->user?->information?->nombre_corto }}
                                        </span>
                                    @endif
                                    <span class="text-xs flex items-center gap-2">
                                        <flux:icon.calendar class="size-5" />
                                        {{ 'Fecha creación : '.$workflow->created_at->translatedFormat('d F Y') }}
                                    </span>
                                    <p class="text-sm">{{ $workflow->observacion ?? '' }}</p>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="-ml-4 mt-5">
                            No hay registros de avance.
                        </li>
                        @endforelse
                    @endif
                </ol>
            </div>
            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button icon="x-mark" variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>

</section>

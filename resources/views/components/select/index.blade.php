{{-- resources/views/components/select/index.blade.php --}}
@props([
    'label' => null,
    'placeholder' => 'Selecciona...',
    'searchable' => false,
    'multiple' => false,
    'size' => 'default',
    'invalid' => null,
])

@php
    $name = $attributes->whereStartsWith('wire:model')->first();
    $invalid ??= ($name && $errors->has($name));

    $classes = Flux::classes()
        ->add('relative flex items-center group w-full transition-all')
        ->add(match ($size) {
            default => 'h-10 py-1.5 px-3 text-base sm:text-sm rounded-lg',
            'sm' => 'h-8 py-1 px-2 text-sm rounded-md',
            'xs' => 'h-6 py-0.5 px-2 text-xs rounded-md',
        })
        ->add('bg-white dark:bg-zinc-700 border shadow-xs')
        ->add($invalid 
            ? 'border-red-500 ring-1 ring-red-500/20' 
            : 'border-zinc-200 border-b-zinc-300/80 dark:border-white/10'
        )
        ->add('focus-within:ring-2 focus-within:ring-zinc-200 focus-within:border-zinc-500 dark:focus-within:border-white/10');

    $labelAttributes = Flux::attributesAfter('label:', $attributes);
    $fieldAttributes = Flux::attributesAfter('field:', $attributes, []);
@endphp

<div class="grid gap-2.5">
    <?php if (isset($label)): ?>
        <flux:label :attributes="$labelAttributes">{{ $label }}</flux:label>
    <?php endif; ?>
    <div 
        x-data="{ 
            open: false, 
            search: '', 
            selected: @entangle($attributes->wire('model')), 
            options: [],
            multiple: @js($multiple),
            toggle(value) {
                if (this.multiple) {
                    this.selected = Array.isArray(this.selected) ? this.selected : [];
                    this.selected.includes(value) ? this.selected = this.selected.filter(i => i !== value) : this.selected.push(value);
                } else {
                    this.selected = value;
                    this.open = false;
                }
            }
        }"
    
        @click.away="open = false"
        {{ $attributes->class($classes) }}
        @if ($invalid) aria-invalid="true" data-invalid @endif
        @isset ($name) name="{{ $name }}" @endisset
        @if (is_numeric($size)) size="{{ $size }}" @endif
    
        data-flux-control
        data-flux-group-target >
        
        {{-- Trigger --}}
        <div @click="open = !open" class="flex justify-between w-full items-center cursor-default min-w-0">
            <template x-if="!multiple || (multiple && (!selected || selected.length === 0))">
                <span class="truncate dark:text-zinc-200" :class="(!selected || selected.length === 0) && 'text-zinc-400 dark:text-zinc-500'">
                    <span x-text="!multiple ? (options.find(o => o.value == selected)?.label || '{{ $placeholder }}') : '{{ $placeholder }}'"></span>
                </span>
            </template>
            
            {{-- Tags para multiselect --}}
            <template x-if="multiple && selected?.length > 0">
                <div class="flex flex-wrap gap-1">
                    <template x-for="val in selected" :key="val">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-zinc-100 dark:bg-white/10 text-xs text-zinc-600 dark:text-zinc-200 border border-zinc-200 dark:border-white/5">
                            <span x-text="options.find(o => o.value == val)?.label || val"></span>
                            <flux:icon.x-mark @click.stop="toggle(val)" variant="micro" class="size-3 cursor-pointer hover:text-red-500" />
                        </span>
                    </template>
                </div>
            </template>
            <flux:icon.chevron-up-down class=" size-5 text-zinc-400 shrink-0 ml-2 group-hover:text-zinc-100" />
        </div>
    
    
        {{-- Dropdown --}}
        <div 
            x-show="open" 
            x-cloak
            x-transition:enter="transition ease-out duration-75"
            x-transition:enter-start="opacity-0 scale-[0.98]"
            x-transition:enter-end="opacity-100 scale-100"
            class="absolute left-0 top-full mt-2 w-full z-[100] bg-white dark:bg-zinc-700 border border-zinc-200 dark:border-white/10 rounded-xl shadow-xl overflow-hidden p-1" >
            @if($searchable)
                <div class="px-2 pt-1 pb-2">
                    <input 
                        x-model="search" 
                        x-ref="searchInput"
                        x-effect="if(open) setTimeout(() => $refs.searchInput.focus(), 100)"
                        type="text" 
                        placeholder="Buscar..."
                        class="w-full px-3 py-1.5 bg-zinc-100 dark:bg-zinc-700 border-none text-sm rounded-lg focus:ring-2 focus:ring-indigo-500/20 dark:text-white dark:placeholder-zinc-500"
                        @click.stop
                    >
                </div>
            @endif
    
            <div class="max-h-60 overflow-y-auto custom-scrollbar">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
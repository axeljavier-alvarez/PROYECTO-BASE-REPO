{{-- resources/views/components/select/option.blade.php --}}
@props([
    'value' => null,
    'label' => null,
])

@php
$classes = Flux::classes()
    ->add('relative flex items-center w-full px-3 py-2 text-sm rounded-md cursor-pointer select-none transition-colors')
    ->add('text-zinc-800 dark:text-zinc-300')
    ->add('hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white')
    ->add('ui-selected:bg-zinc-100 dark:ui-selected:bg-zinc-600 dark:ui-selected:text-white');
@endphp

<div
    x-init="options.push({ value: '{{ $value }}', label: '{{ $label ?? $slot }}' })"
    x-show="search === '' || '{{ $label ?? $slot }}'.toLowerCase().includes(search.toLowerCase())"
    @click="toggle('{{ $value }}')"
    :data-selected="multiple ? (Array.isArray(selected) && selected.includes('{{ $value }}')) : (selected == '{{ $value }}')"
    {{ $attributes->class($classes) }}
    :class="{ 
        'bg-zinc-100 dark:bg-zinc-600 dark:text-white': multiple 
            ? (Array.isArray(selected) && selected.includes('{{ $value }}')) 
            : (selected == '{{ $value }}')
    }"
>
    <span class="flex-1 truncate">
        {{ $slot->isEmpty() ? $label : $slot }}
    </span>

    <template x-if="multiple ? (Array.isArray(selected) && selected.includes('{{ $value }}')) : (selected == '{{ $value }}')">
        <flux:icon.check variant="micro" class="size-4 shrink-0 ml-2" />
    </template>
</div>
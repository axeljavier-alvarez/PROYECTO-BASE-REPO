@props([
    'items' => [],
])

<ol class="flex justify-around flex-wrap">
    @forelse ($items as $item)
        <li 
            wire:click="navToggle({{ $item['option'] }})" 
            class="flex items-center gap-1 text-xs cursor-pointer hover:bg-zinc-300 hover:dark:bg-zinc-600 p-3 rounded-lg {{ $this->nav_option == $item['option'] ? 'bg-zinc-300 dark:bg-zinc-600' : '' }}">
            <flux:icon :name="$item['icon']" class="size-6" />
            <p class="text-wrap">{{ $item['label'] }}</p>
        </li>
    @empty
        <span>No items available</span>
    @endforelse
</ol>
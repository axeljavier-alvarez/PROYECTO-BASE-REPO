@props([
    'items',
    'button_name' => 'Enviar solicitud',
])


<ol class="flex items-center w-full p-3 space-x-2 text-sm font-medium text-center rounded-xl bg-zinc-800 text-zinc-300 border border-default rounded-base shadow-xs sm:p-4 sm:space-x-4 rtl:space-x-reverse">
    @foreach ($items as $item)
    <li 
        wire:click="handleStep('{{ $loop->iteration }}')"
        class="flex items-center cursor-pointer gap-2 uppercase {{ $loop->iteration == $this->step ? 'font-bold text-sky-200' : 'dark:text-zinc-500 text-zinc-400' }}"
        title="{{ $item }}">
        <span class="flex items-center justify-center w-5 h-5 me-2 text-xs border border-brand rounded-full shrink-0" >
            {{ $loop->iteration }}
        </span>
        <span>
            {{ $item }}
        </span>
        @if (!$loop->last)
            <flux:icon name="chevron-double-right" class="size-4" />
        @endif
    </li>
    @endforeach
</ol>

<div class="my-6">
    {{ $slot }}
</div>
<br>
<div class="flex justify-evenly items-center">
    @if ($this->step > 1)
        <flux:button
            wire:click="previousStep()" 
            icon="arrow-left"
            variant="primary"
            color="lime"
            class="text-blue-800">
            Anterior
        </flux:button>
    @endif

    @if ($this->step < count($this->steps))
        <flux:button
            wire:click="nextStep()" 
            icon:trailing="arrow-right"
            variant="primary"
            color="lime"
            class="text-blue-800">
            Siguiente
        </flux:button>
    @endif

    @if($this->step == count($this->steps))
        <flux:button
            wire:click="store()"
            icon:trailing="send-horizontal"
            variant="primary"
            color="sky">
            {{ $button_name }}
        </flux:button>
    @endif

</div>


@if ($errors->any())
<div x-data="{ visible: true }" x-show="visible" x-collapse >
    <div x-show="visible" x-transition>
        <flux:callout 
            icon="finger-print" 
            variant="danger">
            <flux:callout.heading>Ups, algo salio mal</flux:callout.heading>
            <ul>
                @foreach ($errors->all() as $error)
                <li>
                    <flux:callout.text>
                        {{ $error }}   
                    </flux:callout.text>
                </li>
                @endforeach
            </ul>
            <x-slot name="controls">
                <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" />
            </x-slot>
        </flux:callout>
    </div>
</div>
@endif

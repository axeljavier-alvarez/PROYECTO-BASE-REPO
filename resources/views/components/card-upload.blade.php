@props([
    'label' => '',
    'icon' => 'cloud-upload',
    'accept' => '*',
    'description' => '',
    'required' => false,
])

<div {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class' => 'flex flex-col w-full']) }}>
    @if($label)
        <label class="text-sm font-medium mb-1.5 ml-1 flex items-center">
            {{ $label }}
            @if($required) <span class="ml-1" title="Campo requerido">*</span> @endif
        </label>
    @endif

    <div x-data="{ 
            uploading: false, 
            progress: 0,
            fileName: '',
            fileExtension: '',
            
            updateFileInfo(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    this.fileExtension = file.name.split('.').pop();
                }
            },

            resetFile() {
                this.fileName = '';
                this.fileExtension = '';
                this.$refs.fileInput.value = '';
                @if($attributes->wire('model')->value())
                    $wire.set('{{ $attributes->wire('model')->value() }}', null);
                @endif
            }
        }"
        class="relative"
    >
        <label 
            {{-- Cambiamos el cursor si hay error de validación --}}
            x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false"
            x-on:livewire-upload-error="uploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress"
            class="flex flex-col items-center justify-center w-full p-2 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200 
                   hover:bg-gray-50 dark:hover:bg-white/5 border-gray-200 dark:border-gray-700
                   @error($attributes->wire('model')->value()) border-red-500 bg-red-50/50 dark:bg-red-900/10 @enderror"
        >
            <div x-show="!uploading" class="flex flex-col items-center animate-in fade-in duration-300">
                
                <div class="flex justify-center">
                    <template x-if="!fileExtension">
                        <flux:icon :name="$icon" class="size-10 text-gray-400" />
                    </template>
                    
                    <template x-if="fileExtension">
                        <div class="flex flex-col items-center">
                            <div x-show="['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(fileExtension.toLowerCase())">
                                <flux:icon name="file-image" class="size-12 text-blue-500" />
                            </div>
                            <div x-show="fileExtension.toLowerCase() === 'pdf'">
                                <flux:icon name="file-minus" class="size-12 text-red-500" />
                            </div>
                            <div x-show="['xls', 'xlsx', 'csv'].includes(fileExtension.toLowerCase())">
                                <flux:icon name="file-spreadsheet" class="size-12 text-green-600" />
                            </div>
                            <div x-show="['doc', 'docx'].includes(fileExtension.toLowerCase())">
                                <flux:icon name="file-text" class="size-12 text-blue-600" />
                            </div>
                        </div>
                    </template>
                </div>
                
                <div class="text-center mt-2">
                    <template x-if="!fileName">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span class="font-semibold text-accent">Click para subir</span>
                            </p>
                            @if($description)
                                <p class="text-[10px] text-gray-400 mt-1">{{ $description }}</p>
                            @endif
                        </div>
                    </template>

                    <template x-if="fileName">
                        <div class="px-2">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate max-w-[180px]" x-text="fileName"></p>
                            <p class="text-[10px] text-accent font-bold uppercase mt-1">Click para cambiar</p>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="uploading" x-cloak class="absolute inset-0 bg-white/90 dark:bg-zinc-900/90 flex flex-col items-center justify-center px-6 rounded-xl z-10">
                <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                    <div class="bg-accent h-1.5 rounded-full transition-all duration-200" :style="'width: ' + progress + '%'"></div>
                </div>
                <span class="text-[10px] mt-2 font-bold text-accent" x-text="'Subiendo... ' + progress + '%'"></span>
            </div>

            {{-- IMPORTANTE: Quitamos el 'required' nativo para evitar el error 'An invalid form control...' --}}
            {{-- En su lugar, usamos la validación de Livewire --}}
            <input 
                type="file" 
                x-ref="fileInput"
                class="hidden" 
                accept="{{ $accept }}" 
                @change="updateFileInfo" 
                {{ $attributes->whereStartsWith('wire:model') }} 
            />
        </label>

        <template x-if="fileName && !uploading">
            <button 
                type="button"
                @click.stop="resetFile()"
                class="absolute top-2 right-2 p-1.5 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-gray-700 rounded-full text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors shadow-sm z-20"
            >
                <flux:icon name="x-mark" class="size-4" />
            </button>
        </template>
    </div>

    @error($attributes->wire('model')->value())
        <span class="text-xs text-red-500 mt-1.5 ml-1">{{ $message }}</span>
    @enderror
</div>
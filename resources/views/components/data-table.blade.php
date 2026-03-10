<div class="relative overflow-x-auto">
    <div class="p-4 flex items-center justify-between space-x-4">

        <div class="flex gap-2 items-center">
            <flux:select wire:model.live="per_page" wire:key="per-page-select">
                <flux:select.option>5</flux:select.option>
                <flux:select.option>10</flux:select.option>
                <flux:select.option>20</flux:select.option>
                <flux:select.option>50</flux:select.option>
                <flux:select.option>100</flux:select.option>
                <flux:select.option>500</flux:select.option>
                <flux:select.option>1000</flux:select.option>
            </flux:select>

            @if($actions)
                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down">Actions</flux:button>

                    <flux:menu>
                        <flux:menu.item icon="plus">New post</flux:menu.item>

                        <flux:menu.separator />

                        <flux:menu.submenu heading="Sort by">
                            <flux:menu.radio.group>
                                <flux:menu.radio checked>Name</flux:menu.radio>
                                <flux:menu.radio>Date</flux:menu.radio>
                                <flux:menu.radio>Popularity</flux:menu.radio>
                            </flux:menu.radio.group>
                        </flux:menu.submenu>

                        <flux:menu.submenu heading="Filter">
                            <flux:menu.checkbox checked>Draft</flux:menu.checkbox>
                            <flux:menu.checkbox checked>Published</flux:menu.checkbox>
                            <flux:menu.checkbox>Archived</flux:menu.checkbox>
                        </flux:menu.submenu>

                        <flux:menu.separator />

                        <flux:menu.item variant="danger" icon="trash">Delete</flux:menu.item>
                    </flux:menu>
                    
                </flux:dropdown>
            @endif

        </div>

        <div class="flex gap-1">
            <flux:input 
                wire:model.live.debounce.500ms="search" 
                icon="magnifying-glass" 
                placeholder="Buscar ..." 
                type="search" 
                wire:key="search-input" 
            />

            @if($advanceFilter)
            
                <flux:dropdown>
                    <flux:button icon="funnel" />
                    <flux:menu keep-open>
                        <div class="flex justify-center gap-4">
                            <flux:button 
                                wire:click="addFilter()" 
                                icon="plus"
                                size="xs">
                                Agregar filtro
                            </flux:button>
                            <flux:button 
                                wire:click="clearFilters()" 
                                icon="trash"
                                size="xs">
                                Limpiar filtros
                            </flux:button>
                        </div>
                        
                        <flux:menu.separator />

                        @foreach ($this->filters as $index => $filter)
                                                    
                            <flux:menu.item>
                                <div class="flex gap-2 items-center">
                                    
                                    <flux:select
                                        wire:key="field-campo-{{ $index }}" 
                                        wire:model.live.debounce.500ms="filters.{{ $index }}.field" 
                                        size="xs" 
                                        placeholder="Campo">
                                        @foreach ($headers as $header)
                                            @if ($header['label'] !== 'Actions')
                                                <flux:select.option value="{{ $header['index'] }}" >
                                                    {{ $header['label'] }}
                                                </flux:select.option>
                                            @endif
                                        @endforeach                                        
                                    </flux:select>

                                    <flux:select
                                        wire:key="field-operator-{{ $index }}" 
                                        wire:model.live.debounce.500ms="filters.{{ $index }}.operator" 
                                        size="xs" 
                                        placeholder="Operador">
                                        @foreach ($this->getGroupedOperators() as $group => $operators)                                    
                                        <optgroup label="{{ $group }}">
                                            @foreach ($operators as $operator)
                                                <flux:select.option value="{{ $operator }}">{{ $operator }}</flux:select.option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                    </flux:select>

                                    <flux:input
                                        x-on:keydown.stop=""
                                        wire:key="field-value-{{ $index }}" 
                                        wire:model.live.debounce.500ms="filters.{{ $index }}.value" 
                                        size="xs" 
                                        placeholder="Valores" 
                                    />

                                    <flux:icon.x-circle wire:click="deleteFilter({{ $index }})" class="cursor-pointer text-red-500"/>
                                </div>
                            </flux:menu.item>
                                    
                        @endforeach
                    </flux:menu>
                </flux:dropdown>
                
            @endif
        </div>

    </div>

    <div class="lg:hidden">
        @forelse ($rows as $index => $rowData)
            <flux:card class="my-4">
                <flux:table>
                    @if($selectable)
                        <flux:table.rows>
                            <flux:table.cell align="start">
                                <flux:checkbox wire:model.live="selectedRows" value="{{ $rowData->id }}" />
                            </flux:table.cell>
                        </flux:table.rows>
                    @endif
                    @foreach($headers as $header)
                        <flux:table.rows class="even:bg-zinc-100 dark:even:bg-zinc-600">
                            @php
                                $columnIndex = $header['index'];
                                $slotKey = 'column_' . str_replace('.', '_', $columnIndex);
                                $slot = $capturedSlots[$slotKey] ?? null;
                            @endphp
                            <flux:table.cell 
                                align="start">
                                <span class="font-medium uppercase pl-4">
                                    {{ $header['label'] . ($header['index'] !== 'actions' ? ' :' : '') }}
                                </span>
                            </flux:table.cell>
                            <flux:table.cell 
                                title="{{ data_get($rowData, $columnIndex, '') }}"
                                align="end">
                                <div class="pr-4">
                                    @if($slot)
                                        @php
                                            try {
                                                $result = $slot->call($this, $rowData, $loop ?? null);
                                                echo $result instanceof HtmlString ? $result->toHtml() : $result;
                                            } catch (\Throwable $e) {
                                                $context = ['row' => $rowData, 'loop' => $loop ?? null];
                                                $result = app()->call($slot, $context);
                                                echo $result instanceof HtmlString ? $result->toHtml() : $result;
                                            }
                                        @endphp
                                    @else
                                        {{ data_get($rowData, $columnIndex, '') }}
                                    @endif
                                </div>
                            </flux:table.cell>
                        </flux:table.rows>
                    @endforeach
                </flux:table>
            </flux:card>
        @empty
            
        @endforelse
        

        <flux:pagination :paginator="$rows" />
    </div>

    <div class="hidden lg:block">
        <flux:table :paginate="$rows">
            <flux:table.columns class="bg-white dark:bg-zinc-900">
                @if($selectable)
                    <flux:table.column >
                        <div class="flex items-center">
                            {{-- <flux:checkbox wire:change="selectedAllCurrentPage({{ json_encode($rows) }})" /> --}}
                        </div>
                    </flux:table.column>
                @endif
                @foreach ($headers as $header)
                    <flux:table.column
                        :sortable="$header['index'] !== 'actions'" 
                        :sorted="$this->sortBy === $header['index']" 
                        :direction="$this->sortDirection" 
                        wire:click="sort('{{ ($header['index'] !='actions') ?  $header['index'] : '' }}')"
                        align="{{ $header['align'] ?? 'start' }}"
                        width="{{ $header['width'] ?? null }}">
                        <span
                            class="{{ $header['class'] ?? '' }} cursor-pointer" >
                            {{ $header['label'] }}
                        </span>
                    </flux:table.column>
                @endforeach
            </flux:table.columns>

            <flux:table.rows>
                @foreach($rows as $index => $rowData)
                    <flux:table.row
                        wire:key="{{ $rowData->id }}"
                        class="dark:hover:bg-neutral-700 hover:bg-neutral-100 text-neutral-600 dark:text-neutral-200">
                        @if($selectable)
                            <flux:table.cell 
                                align="{{ $header['align'] ?? 'start' }}"
                                width="{{ $header['width'] ?? null }}">
                                <div class="flex items-center">
                                    <flux:checkbox wire:model.live="selectedRows" value="{{ $rowData->id }}" />
                                </div>
                            </flux:table.cell>
                        @endif
                        @foreach($headers as $header)
                            @php
                                $columnIndex = $header['index'];
                                $slotKey = 'column_' . str_replace('.', '_', $columnIndex);
                                $slot = $capturedSlots[$slotKey] ?? null;
                            @endphp
                            <flux:table.cell 
                                title="{{ data_get($rowData, $columnIndex, '') }}"
                                align="{{ $header['align'] ?? 'start' }}"
                                width="{{ $header['width'] ?? null }}">
                                @if($slot)
                                    @php
                                        try {
                                            $result = $slot->call($this, $rowData, $loop ?? null);
                                            echo $result instanceof HtmlString ? $result->toHtml() : $result;
                                        } catch (\Throwable $e) {
                                            $context = ['row' => $rowData, 'loop' => $loop ?? null];
                                            $result = app()->call($slot, $context);
                                            echo $result instanceof HtmlString ? $result->toHtml() : $result;
                                        }
                                    @endphp
                                @else
                                    {{ data_get($rowData, $columnIndex, '') }}
                                @endif
                            </flux:table.cell>
                        @endforeach
                    </flux:table.row>
                @endforeach
            </flux:table.rows>        
        </flux:table>
    </div>

</div>
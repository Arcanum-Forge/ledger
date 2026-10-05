{{-- kingdoms/index.blade.php --}}
<div>

    @island(name: 'flash', always: true)
        <div>
            <x-flash-message />
        </div>
    @endisland

    <x-page-header eyebrow="The World" title="Kingdoms">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Record Kingdom</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    @island(name: 'summary', lazy: true, always: true)
        @placeholder
            <x-summary-cards>
                <x-summary-card label="Kingdoms Shown">
                    <div class="h-9 w-14 animate-pulse bg-line/60"></div>
                </x-summary-card>
                <x-summary-card label="Average Threat">
                    <div class="h-9 w-14 animate-pulse bg-line/60"></div>
                </x-summary-card>
                <x-summary-card label="Highest Threat" :last="true">
                    <div class="h-7 w-32 animate-pulse bg-line/60"></div>
                </x-summary-card>
            </x-summary-cards>
        @endplaceholder

        @php
            $summary = $this->summary
        @endphp

        <x-summary-cards>
            <x-summary-card label="Kingdoms Shown">
                <p class="font-serif text-3xl text-parchment">{{ $summary['total'] }}</p>
            </x-summary-card>
            <x-summary-card label="Average Threat">
                <p class="font-serif text-3xl text-parchment">{{ $summary['avgThreat'] }}</p>
            </x-summary-card>
            <x-summary-card label="Highest Threat" :last="true">
                <p class="font-serif text-lg text-gold">
                    {{ $summary['highest']?->name ?? '—' }}
                    @if ($summary['highest'])
                        <span class="text-sm text-faint">({{ $summary['highest']->threat }})</span>
                    @endif
                </p>
            </x-summary-card>
        </x-summary-cards>
    @endisland

    <x-filter-panel :active="$this->filtersActive">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-text-input model="search" label="Search" placeholder="Name, title..." variant="filter" :span="true" />

            <x-select-input model="alignmentFilter" label="Alignment" :options="$alignments" variant="filter"
                placeholder="Any" />

            <x-relation-picker label="Ruler" search-model="rulerSearch" placeholder="Search rulers..."
                :options="$this->rulerResults" :selected-id="$rulerFilter" :selected-name="$rulerFilterName"
                select-action="selectRulerFilter" clear-action="clearRulerFilter" />

            <x-relation-picker label="Region" search-model="regionSearch" placeholder="Search regions..."
                :options="$this->regionResults" :selected-id="$regionFilter" :selected-name="$regionFilterName"
                select-action="selectRegionFilter" clear-action="clearRegionFilter" />

            <x-range-filter-field label="Threat" min-model="minThreat" max-model="maxThreat" :min-value="$minThreat"
                :max-value="$maxThreat" />
        </div>
    </x-filter-panel>

    @island(name: 'table', lazy: true, always: true)
        @placeholder
            <div class="border border-line bg-card">
                <div class="min-h-[28rem] animate-pulse divide-y divide-line/60">
                    <div class="flex gap-6 px-5 py-4">
                        <div class="h-2.5 w-24 bg-line/60"></div>
                        <div class="h-2.5 w-16 bg-line/60"></div>
                        <div class="h-2.5 w-16 bg-line/60"></div>
                        <div class="h-2.5 w-16 bg-line/60"></div>
                        <div class="h-2.5 w-20 bg-line/60"></div>
                    </div>
                    @foreach (range(1, 6) as $i)
                        <div class="flex items-center gap-6 px-5 py-5">
                            <div class="h-3.5 w-40 bg-line/60"></div>
                            <div class="h-3 w-28 bg-line/40"></div>
                            <div class="h-3 w-24 bg-line/40"></div>
                            <div class="h-3.5 w-8 bg-line/60"></div>
                            <div class="h-3 flex-1 bg-line/40"></div>
                            <div class="h-3 w-20 bg-line/60"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endplaceholder

        @php
            $kingdoms = $this->kingdoms
        @endphp

        <div class="border border-line bg-card">
            {{-- Desktop / Tablet --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[800px] text-left">
                    <thead>
                        <tr class="border-b border-line text-[8px] uppercase tracking-[0.2em] text-faint">
                            @foreach ([
                                    'name' => 'Name',
                                    'ruler' => 'Ruler',
                                    'region' => 'Region',
                                    'threat' => 'Threat',
                                    'alignment' => 'Alignment',
                                ] as $col => $label)
                                <x-sortable-th :column="$col" :label="$label" :sort-by="$this->sortBy"
                                    :sort-direction="$this->sortDirection" />
                            @endforeach

                            <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-line/60">
                        @forelse ($kingdoms as $kingdom)
                            <tr class="transition hover:bg-card-hover">
                                <td class="px-5 py-4">
                                    {{-- wire:island="modal": the modal lives in its own island, so scope the update there --}}
                                    <button wire:click="openView('{{ $kingdom->slug }}')" wire:island="modal"
                                        class="text-left">
                                        <div class="font-serif text-sm text-parchment">{{ $kingdom->name }}</div>
                                        <div class="text-[9px] uppercase tracking-[0.15em] text-faint">
                                            {{ $kingdom->title }}
                                        </div>
                                    </button>
                                </td>

                                <td class="px-5 py-4 text-xs text-muted">
                                    {{ $kingdom->ruler->full_title ?? 'Unclaimed' }}
                                </td>
                                <td class="px-5 py-4 text-xs text-muted">{{ $kingdom->region->name ?? 'Unknown' }}</td>
                                <td class="px-5 py-4 text-sm font-semibold {{ $kingdom->threat_color }}">
                                    {{ $kingdom->threat }}
                                </td>
                                <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em] text-muted">
                                    {{ $kingdom->alignment }}
                                </td>

                                <td class="px-5 py-4">
                                    <x-row-actions :id="$kingdom->slug" island="modal" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <x-empty-state title="No kingdoms recorded."
                                        hint="Begin by establishing the first realm." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="divide-y divide-line/60 md:hidden">
                @forelse ($kingdoms as $kingdom)
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <button wire:click="openView('{{ $kingdom->slug }}')" wire:island="modal"
                                class="min-w-0 text-left">
                                <div class="font-serif text-base text-parchment">{{ $kingdom->name }}</div>
                                <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-faint">
                                    {{ $kingdom->title }}
                                </div>
                            </button>
                            <div class="shrink-0 text-right">
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Threat</div>
                                <div class="mt-1 text-sm font-semibold {{ $kingdom->threat_color }}">
                                    {{ $kingdom->threat }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Ruler</div>
                                <div class="mt-1 text-xs text-muted">{{ $kingdom->ruler->name ?? 'Unclaimed' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Region</div>
                                <div class="mt-1 text-xs text-muted">{{ $kingdom->region->name ?? 'Unknown' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Alignment</div>
                                <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-muted">
                                    {{ $kingdom->alignment }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-end gap-5 border-t border-line/60 pt-4">
                            <x-row-actions :id="$kingdom->slug" island="modal" />
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-16 text-center">
                        <x-empty-state title="No kingdoms recorded." hint="Begin by establishing the first realm." />
                    </div>
                @endforelse
            </div>
        </div>

        <x-cursor-pagination :paginator="$kingdoms" :total="$this->total" />
    @endisland

    @island(name: 'modal', always: true)
        <x-modal-shell>
            @if ($this->showModal)
                @if ($this->modalMode === 'view' && $this->selected)
                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-8 bg-line"></span>
                        <span
                            class="text-[9px] uppercase tracking-[0.28em] text-gold-dim">{{ $this->selected->region->name ?? 'Unknown' }}</span>
                    </div>

                    <h2 class="font-serif text-3xl text-parchment-bright">{{ $this->selected->name }}</h2>
                    <p class="mt-1 text-xs uppercase tracking-[0.2em] text-line">{{ $this->selected->title }}</p>
                    <p class="mt-5 text-sm leading-7 text-muted">{{ $this->selected->description }}</p>

                    <div class="mt-7 grid grid-cols-2 gap-5 border-t border-line pt-6 text-xs">
                        <x-detail-item
                            label="Ruler">{{ $this->selected->ruler->full_title ?? 'Unclaimed' }}</x-detail-item>
                        <x-detail-item label="Population">{{ $this->selected->population }}</x-detail-item>
                        <x-detail-item label="Alignment">{{ $this->selected->alignment }}</x-detail-item>
                        <x-detail-item label="Founded">{{ $this->selected->founded }}</x-detail-item>
                        <x-detail-item label="Threat"
                            class="font-semibold {{ $this->selected->threat_color }}">{{ $this->selected->threat }}</x-detail-item>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <x-btn-secondary wire:click="closeModal" class="flex-1">Close</x-btn-secondary>
                        <x-btn-primary wire:click="switchToEdit" class="flex-1">Edit</x-btn-primary>
                    </div>

                @elseif ($this->modalMode === 'delete' && $this->selected)
                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-8 bg-danger/60"></span>
                        <span class="text-[9px] uppercase tracking-[0.28em] text-danger">Strike from the Record</span>
                    </div>

                    <h2 class="font-serif text-2xl text-parchment-bright">Remove {{ $this->selected->name }}?</h2>
                    <p class="mt-3 text-sm leading-6 text-muted">
                        This permanently erases <span class="text-parchment-dim">{{ $this->selected->name }}</span>,
                        {{ $this->selected->title }}, from the archive. This cannot be undone.
                    </p>

                    <div class="mt-8 flex gap-3">
                        <x-btn-secondary wire:click="closeModal" class="flex-1">Cancel</x-btn-secondary>
                        <x-btn-danger wire:click="confirmDelete" wire:loading.attr="disabled"
                            wire:target="confirmDelete" class="flex-1">
                            <span wire:loading.remove wire:target="confirmDelete">Confirm Removal</span>
                            <span wire:loading wire:target="confirmDelete">Removing...</span>
                        </x-btn-danger>
                    </div>

                @else
                    <h2 class="mb-6 font-serif text-2xl text-parchment-bright">
                        {{ $this->modalMode === 'edit' ? 'Amend Record' : 'Establish a New Realm' }}
                    </h2>

                    <form wire:submit="save" class="space-y-4">
                        <x-text-input model="name" label="Name" />
                        <x-text-input model="title" label="Title" placeholder="e.g. The Golden Kingdom" />
                        <x-textarea-input model="description" label="Description" :rows="3" />

                        <div class="grid grid-cols-2 gap-4">
                            <x-relation-picker label="Ruler" search-model="rulerFormSearch"
                                placeholder="Search rulers..." :options="$this->rulerFormResults"
                                :selected-id="$this->rulerId" :selected-name="$this->rulerName"
                                select-action="selectRulerForm" clear-action="clearRulerForm"
                                clear-label="Unclaimed" option-label-key="label" />
                            <x-text-input model="population" label="Population" placeholder="e.g. 2.8 million" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-relation-picker label="Region" search-model="regionFormSearch"
                                    placeholder="Search regions..." :options="$this->regionFormResults"
                                    :selected-id="$this->regionId" :selected-name="$this->regionName"
                                    select-action="selectRegionForm" clear-action="clearRegionForm"
                                    clear-label="Unknown" option-label-key="label" />
                                @error('regionId')
                                    <p class="mt-1.5 text-[10px] text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <x-text-input model="founded" label="Founded" placeholder="e.g. Year 184" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <x-select-input model="alignment" label="Alignment" :options="$this->alignments" />
                            <x-range-field model="threat" label="Threat Level" />
                        </div>

                        <div class="flex gap-3 pt-2">
                            <x-btn-secondary type="button" wire:click="closeModal"
                                class="flex-1">Cancel</x-btn-secondary>
                            <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="flex-1">
                                <span wire:loading.remove
                                    wire:target="save">{{ $this->modalMode === 'edit' ? 'Save Changes' : 'Record Kingdom' }}</span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </x-btn-primary>
                        </div>
                    </form>
                @endif
            @endif
        </x-modal-shell>
    @endisland
</div>
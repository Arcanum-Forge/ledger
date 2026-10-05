<div>

    <x-flash-message />

    <x-page-header eyebrow="The World" title="Kingdoms">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Record Kingdom</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

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

    <x-filter-panel :active="$search || $alignmentFilter || $regionFilter || $rulerFilter || $minThreat > 0 || $maxThreat < 100">
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

    {{-- Table --}}
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
                            <x-sortable-th :column="$col" :label="$label" :sort-by="$sortBy"
                                :sort-direction="$sortDirection" />
                        @endforeach

                        <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-line/60">
                    @forelse ($kingdoms as $kingdom)
                        <tr class="transition hover:bg-card-hover">
                            <td class="px-5 py-4">
                                <button wire:click="openView('{{ $kingdom->slug }}')" class="text-left">
                                    <div class="font-serif text-sm text-parchment">{{ $kingdom->name }}</div>
                                    <div class="text-[9px] uppercase tracking-[0.15em] text-faint">{{ $kingdom->title }}
                                    </div>
                                </button>
                            </td>

                            <td class="px-5 py-4 text-xs text-muted">{{ $kingdom->ruler->full_title ?? 'Unclaimed' }}
                            </td>
                            <td class="px-5 py-4 text-xs text-muted">{{ $kingdom->region->name ?? 'Unknown' }}</td>
                            <td class="px-5 py-4 text-sm font-semibold {{ $kingdom->threat_color }}">{{ $kingdom->threat }}
                            </td>
                            <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em] text-muted">
                                {{ $kingdom->alignment }}
                            </td>

                            <td class="px-5 py-4">
                                <x-row-actions :id="$kingdom->slug" />
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
                        <button wire:click="openView('{{ $kingdom->slug }}')" class="min-w-0 text-left">
                            <div class="font-serif text-base text-parchment">{{ $kingdom->name }}</div>
                            <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-faint">{{ $kingdom->title }}
                            </div>
                        </button>
                        <div class="shrink-0 text-right">
                            <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Threat</div>
                            <div class="mt-1 text-sm font-semibold {{ $kingdom->threat_color }}">{{ $kingdom->threat }}
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
                        <x-row-actions :id="$kingdom->slug" />
                    </div>
                </div>
            @empty
                <div class="px-5 py-16 text-center">
                    <x-empty-state title="No kingdoms recorded." hint="Begin by establishing the first realm." />
                </div>
            @endforelse
        </div>
    </div>

    <x-cursor-pagination :paginator="$kingdoms" :total="$summary['total']" />

    <x-modal-shell>
        @if ($showModal)
            @if ($modalMode === 'view' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-line"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-gold-dim">{{ $selected->region->name }}</span>
                </div>

                <h2 class="font-serif text-3xl text-parchment-bright">{{ $selected->name }}</h2>
                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-line">{{ $selected->title }}</p>
                <p class="mt-5 text-sm leading-7 text-muted">{{ $selected->description }}</p>

                <div class="mt-7 grid grid-cols-2 gap-5 border-t border-line pt-6 text-xs">
                    <x-detail-item label="Ruler">{{ $selected->ruler->full_title }}</x-detail-item>
                    <x-detail-item label="Population">{{ $selected->population }}</x-detail-item>
                    <x-detail-item label="Alignment">{{ $selected->alignment }}</x-detail-item>
                    <x-detail-item label="Founded">{{ $selected->founded }}</x-detail-item>
                    <x-detail-item label="Threat"
                        class="font-semibold {{ $selected->threat_color }}">{{ $selected->threat }}</x-detail-item>
                </div>

                <div class="mt-8 flex gap-3">
                    <x-btn-secondary wire:click="closeModal" class="flex-1">Close</x-btn-secondary>
                    <x-btn-primary wire:click="switchToEdit" class="flex-1">Edit</x-btn-primary>
                </div>

            @elseif ($modalMode === 'delete' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-danger/60"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-danger">Strike from the Record</span>
                </div>

                <h2 class="font-serif text-2xl text-parchment-bright">Remove {{ $selected->name }}?</h2>
                <p class="mt-3 text-sm leading-6 text-muted">
                    This permanently erases <span class="text-parchment-dim">{{ $selected->name }}</span>,
                    {{ $selected->title }}, from the archive. This cannot be undone.
                </p>

                <div class="mt-8 flex gap-3">
                    <x-btn-secondary wire:click="closeModal" class="flex-1">Cancel</x-btn-secondary>
                    <x-btn-danger wire:click="confirmDelete" wire:loading.attr="disabled" wire:target="confirmDelete"
                        class="flex-1">
                        <span wire:loading.remove wire:target="confirmDelete">Confirm Removal</span>
                        <span wire:loading wire:target="confirmDelete">Removing...</span>
                    </x-btn-danger>
                </div>

            @else
                <h2 class="mb-6 font-serif text-2xl text-parchment-bright">
                    {{ $modalMode === 'edit' ? 'Amend Record' : 'Establish a New Realm' }}
                </h2>

                <form wire:submit="save" class="space-y-4">
                    <x-text-input model="name" label="Name" />
                    <x-text-input model="title" label="Title" placeholder="e.g. The Golden Kingdom" />
                    <x-textarea-input model="description" label="Description" :rows="3" />

                    <div class="grid grid-cols-2 gap-4">
                        <x-relation-picker label="Ruler" search-model="rulerFormSearch" placeholder="Search rulers..."
                            :options="$this->rulerFormResults" :selected-id="$rulerId" :selected-name="$rulerName"
                            select-action="selectRulerForm" clear-action="clearRulerForm" clear-label="Unclaimed"
                            option-label-key="label" />
                        <x-text-input model="population" label="Population" placeholder="e.g. 2.8 million" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-relation-picker label="Region" search-model="regionFormSearch" placeholder="Search regions..."
                                :options="$this->regionFormResults" :selected-id="$regionId" :selected-name="$regionName"
                                select-action="selectRegionForm" clear-action="clearRegionForm" clear-label="Unknown"
                                option-label-key="label" />
                            @error('regionId')
                            <p class="mt-1.5 text-[10px] text-danger">{{ $message }}</p> @enderror
                        </div>
                        <x-text-input model="founded" label="Founded" placeholder="e.g. Year 184" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-select-input model="alignment" label="Alignment" :options="$alignments" />
                        <x-range-field model="threat" label="Threat Level" />
                    </div>

                    <div class="flex gap-3 pt-2">
                        <x-btn-secondary type="button" wire:click="closeModal" class="flex-1">Cancel</x-btn-secondary>
                        <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save" class="flex-1">
                            <span wire:loading.remove
                                wire:target="save">{{ $modalMode === 'edit' ? 'Save Changes' : 'Record Kingdom' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </x-btn-primary>
                    </div>
                </form>
            @endif
        @endif
    </x-modal-shell>
</div>
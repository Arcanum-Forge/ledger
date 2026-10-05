<div>

    <x-flash-message />

    <x-page-header eyebrow="The Watch" title="Threat Reports">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">File Report</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    <x-summary-cards>
        <x-summary-card label="Reports Shown">
            <p class="font-serif text-3xl text-parchment">{{ $summary['total'] }}</p>
        </x-summary-card>
        <x-summary-card label="Average Sightings">
            <p class="font-serif text-3xl text-parchment">{{ $summary['avgSightings'] }}</p>
        </x-summary-card>
        <x-summary-card label="Most Severe" :last="true">
            <p class="font-serif text-lg text-gold">
                {{ $summary['mostSevere']?->title ?? '—' }}
                @if ($summary['mostSevere'])
                    <span class="text-sm text-faint">({{ $summary['mostSevere']->level }})</span>
                @endif
            </p>
        </x-summary-card>
    </x-summary-cards>

    <x-filter-panel :active="$search || $typeFilter || $levelFilter || $statusFilter || $regionFilter || $kingdomFilter || $minSightings > 0 || $maxSightings < 100">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <x-text-input model="search" label="Search" placeholder="Title, report number, description..."
                variant="filter" :span="true" />

            <x-select-input model="typeFilter" label="Type" :options="$types" variant="filter" placeholder="Any" />

            <x-select-input model="levelFilter" label="Level" :options="$levels" variant="filter" placeholder="Any" />

            <x-select-input model="statusFilter" label="Status" :options="$statuses" variant="filter"
                placeholder="Any" />

            <x-relation-picker label="Region" search-model="regionFilterSearch" placeholder="Search regions..."
                :options="$this->regionFilterResults" :selected-id="$regionFilter" :selected-name="$regionFilterName"
                select-action="selectRegionFilter" clear-action="clearRegionFilter" />

            <x-relation-picker label="Kingdom" search-model="kingdomFilterSearch" placeholder="Search kingdoms..."
                :options="$this->kingdomFilterResults" :selected-id="$kingdomFilter" :selected-name="$kingdomFilterName"
                select-action="selectKingdomFilter" clear-action="clearKingdomFilter" />

            <x-range-filter-field label="Sightings" min-model="minSightings" max-model="maxSightings"
                :min-value="$minSightings" :max-value="$maxSightings" />
        </div>
    </x-filter-panel>

    {{-- Table --}}
    <div class="border border-line bg-card">
        {{-- Desktop / Tablet --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[950px] text-left">
                <thead>
                    <tr class="border-b border-line text-[8px] uppercase tracking-[0.2em] text-faint">
                        @foreach (['report_number' => 'Report #', 'title' => 'Title', 'type' => 'Type', 'level' => 'Level', 'status' => 'Status', 'sightings' => 'Sightings'] as $col => $label)
                            <x-sortable-th :column="$col" :label="$label" :sort-by="$sortBy"
                                :sort-direction="$sortDirection" />
                        @endforeach

                        <th class="whitespace-nowrap px-5 py-4">Region</th>
                        <th class="whitespace-nowrap px-5 py-4">Kingdom</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-line/60">
                    @forelse ($reports as $report)
                                    <tr class="transition hover:bg-card-hover">
                                        <td class="px-5 py-4 text-xs text-faint">{{ $report->report_number }}</td>

                                        <td class="px-5 py-4">
                                            <button wire:click="openView('{{ $report->slug }}')" class="text-left">
                                                <div class="font-serif text-sm text-parchment">{{ $report->title }}</div>
                                            </button>
                                        </td>

                                        <td class="px-5 py-4 text-xs text-muted">{{ $report->type }}</td>

                                        <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em]">
                                            <x-severity-text :value="$report->level" :colors="[
                            'Critical' => 'text-danger',
                            'Severe' => 'text-gold',
                        ]" default="text-gold" />
                                        </td>

                                        <td class="px-5 py-4 text-xs text-muted">{{ $report->status }}</td>
                                        <td class="px-5 py-4 text-sm font-semibold text-parchment">{{ $report->sightings }}</td>
                                        <td class="px-5 py-4 text-xs text-muted">{{ $report->region->name ?? '—' }}</td>
                                        <td class="px-5 py-4 text-xs text-muted">{{ $report->kingdom->name ?? 'Unconfirmed' }}</td>

                                        <td class="px-5 py-4">
                                            <x-row-actions :id="$report->slug" />
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-16 text-center">
                                <x-empty-state title="No reports filed." hint="Begin by filing the first report."
                                    :filtered="$search || $typeFilter || $levelFilter || $statusFilter || $regionFilter || $kingdomFilter || $minSightings > 0 || $maxSightings < 100"
                                    filtered-title="No reports match these filters." filtered-hint="to see the full log." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="divide-y divide-line/60 md:hidden">
            @forelse ($reports as $report)
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <button wire:click="openView('{{ $report->slug }}')" class="min-w-0 text-left">
                                <div class="font-serif text-base text-parchment">{{ $report->title }}</div>
                                <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-faint">
                                    {{ $report->report_number }}
                                </div>
                            </button>
                            <div class="shrink-0 text-right">
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Level</div>
                                <div class="mt-1 text-sm font-semibold">
                                    <x-severity-text :value="$report->level" :colors="[
                    'Critical' => 'text-danger',
                    'Severe' => 'text-gold',
                ]" default="text-gold" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Region</div>
                                <div class="mt-1 text-xs text-muted">{{ $report->region->name ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Kingdom</div>
                                <div class="mt-1 text-xs text-muted">{{ $report->kingdom->name ?? 'Unconfirmed' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Status</div>
                                <div class="mt-1 text-xs text-muted">{{ $report->status }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Sightings</div>
                                <div class="mt-1 text-xs text-muted">{{ $report->sightings }}</div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-end gap-5 border-t border-line/60 pt-4">
                            <x-row-actions :id="$report->slug" />
                        </div>
                    </div>
            @empty
                <div class="px-5 py-16 text-center">
                    <x-empty-state title="No reports filed." hint="Begin by filing the first report." :filtered="$search || $typeFilter || $levelFilter || $statusFilter || $regionFilter || $kingdomFilter || $minSightings > 0 || $maxSightings < 100" filtered-title="No reports match these filters."
                        filtered-hint="to see the full log." />
                </div>
            @endforelse
        </div>
    </div>

    <x-cursor-pagination :paginator="$reports" :total="$summary['total']" />

    <x-modal-shell>
        @if ($showModal)
            @if ($modalMode === 'view' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-line"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-gold-dim">{{ $selected->report_number }}</span>
                </div>

                <h2 class="font-serif text-3xl text-parchment-bright">{{ $selected->title }}</h2>
                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-line">{{ $selected->type }}</p>

                <p class="mt-5 text-sm leading-7 text-muted">{{ $selected->description ?? 'No further details on file.' }}
                </p>

                <div class="mt-7 grid grid-cols-2 gap-5 border-t border-line pt-6 text-xs">
                    <x-detail-item label="Region">{{ $selected->region->name ?? '—' }}</x-detail-item>
                    <x-detail-item label="Kingdom">{{ $selected->kingdom->name ?? 'Unconfirmed' }}</x-detail-item>
                    <div>
                        <p class="text-[8px] uppercase tracking-[0.15em] text-faint">Level</p>
                        <p class="mt-1 font-semibold">
                            <x-severity-text :value="$selected->level" default="text-parchment-dim" :colors="[
                        'Critical' => 'text-danger',
                        'Severe' => 'text-gold',
                    ]" />
                        </p>
                    </div>
                    <x-detail-item label="Status">{{ $selected->status }}</x-detail-item>
                    <x-detail-item label="Sightings">{{ $selected->sightings }}</x-detail-item>
                </div>

                <div class="mt-8 flex gap-3">
                    <x-btn-secondary wire:click="closeModal" class="flex-1">Close</x-btn-secondary>
                    <x-btn-primary wire:click="switchToEdit" class="flex-1">Edit</x-btn-primary>
                </div>

            @elseif ($modalMode === 'delete' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-danger/60"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-danger">Strike from the Log</span>
                </div>

                <h2 class="font-serif text-2xl text-parchment-bright">Remove {{ $selected->title }}?</h2>
                <p class="mt-3 text-sm leading-6 text-muted">
                    This permanently erases report <span class="text-parchment-dim">{{ $selected->report_number }}</span> from the
                    log. This cannot be undone.
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
                    {{ $modalMode === 'edit' ? 'Amend Report' : 'File a New Report' }}
                </h2>

                <form wire:submit="save" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <x-text-input model="reportNumber" label="Report #" placeholder="e.g. TR-0842" />
                        <x-text-input model="sightings" label="Sightings" type="number" min="0" />
                    </div>

                    <x-text-input model="title" label="Title" />
                    <x-textarea-input model="description" label="Description" :rows="3" />

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-relation-picker label="Region" search-model="regionFormSearch" placeholder="Search regions..."
                                :options="$this->regionFormResults" :selected-id="$regionId" :selected-name="$regionName"
                                select-action="selectRegionForm" clear-action="clearRegionForm" clear-label="Unknown"
                                option-label-key="label" />
                            @error('regionId')
                            <p class="mt-1.5 text-[10px] text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-relation-picker label="Kingdom" search-model="kingdomFormSearch" placeholder="Search kingdoms..."
                                :options="$this->kingdomFormResults" :selected-id="$kingdomId" :selected-name="$kingdomName"
                                select-action="selectKingdomForm" clear-action="clearKingdomForm" clear-label="Unconfirmed"
                                option-label-key="label" />
                            @error('kingdomId')
                            <p class="mt-1.5 text-[10px] text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <x-select-input model="type" label="Type" :options="$types" />
                        <x-select-input model="level" label="Level" :options="$levels" />
                        <x-select-input model="status" label="Status" :options="$statuses" />
                    </div>

                    <div class="flex gap-3 pt-2">
                        <x-btn-secondary type="button" wire:click="closeModal" class="flex-1">Cancel</x-btn-secondary>
                        <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save" class="flex-1">
                            <span wire:loading.remove
                                wire:target="save">{{ $modalMode === 'edit' ? 'Save Changes' : 'File Report' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </x-btn-primary>
                    </div>
                </form>
            @endif
        @endif
    </x-modal-shell>
</div>
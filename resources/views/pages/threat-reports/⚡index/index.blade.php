<div>

    <x-flash-message />

    <x-page-header eyebrow="The Watch" title="Threat Reports">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">File Report</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    <x-summary-cards>
        <x-summary-card label="Reports Shown">
            <p class="font-serif text-3xl text-[#d8c8a8]">{{ $summary['total'] }}</p>
        </x-summary-card>
        <x-summary-card label="Average Sightings">
            <p class="font-serif text-3xl text-[#d8c8a8]">{{ $summary['avgSightings'] }}</p>
        </x-summary-card>
        <x-summary-card label="Most Severe" :last="true">
            <p class="font-serif text-lg text-[#b98967]">
                {{ $summary['mostSevere']?->title ?? '—' }}
                @if ($summary['mostSevere'])
                    <span class="text-sm text-[#756d5e]">({{ $summary['mostSevere']->level }})</span>
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
    <div class="border border-[#2c2922] bg-[#151310]">
        {{-- Desktop / Tablet --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[950px] text-left">
                <thead>
                    <tr class="border-b border-[#2c2922] text-[8px] uppercase tracking-[0.2em] text-[#625744]">
                        @foreach (['report_number' => 'Report #', 'title' => 'Title', 'type' => 'Type', 'level' => 'Level', 'status' => 'Status', 'sightings' => 'Sightings'] as $col => $label)
                            <x-sortable-th :column="$col" :label="$label" :sort-by="$sortBy"
                                :sort-direction="$sortDirection" />
                        @endforeach

                        <th class="whitespace-nowrap px-5 py-4">Region</th>
                        <th class="whitespace-nowrap px-5 py-4">Kingdom</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#2c2922]/60">
                    @forelse ($reports as $report)
                                    <tr class="transition hover:bg-[#191611]">
                                        <td class="px-5 py-4 text-xs text-[#625744]">{{ $report->report_number }}</td>

                                        <td class="px-5 py-4">
                                            <button wire:click="openView('{{ $report->slug }}')" class="text-left">
                                                <div class="font-serif text-sm text-[#ddd2bb]">{{ $report->title }}</div>
                                            </button>
                                        </td>

                                        <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $report->type }}</td>

                                        <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em]">
                                            <x-severity-text :value="$report->level" :colors="[
                            'Critical' => 'text-[#c14545]',
                            'Severe' => 'text-[#b98967]',
                        ]" default="text-[#c59b4a]" />
                                        </td>

                                        <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $report->status }}</td>
                                        <td class="px-5 py-4 text-sm font-semibold text-[#d8c8a8]">{{ $report->sightings }}</td>
                                        <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $report->region->name ?? '—' }}</td>
                                        <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $report->kingdom->name ?? 'Unconfirmed' }}</td>

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
        <div class="divide-y divide-[#2c2922]/60 md:hidden">
            @forelse ($reports as $report)
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <button wire:click="openView('{{ $report->slug }}')" class="min-w-0 text-left">
                                <div class="font-serif text-base text-[#ddd2bb]">{{ $report->title }}</div>
                                <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-[#625744]">
                                    {{ $report->report_number }}</div>
                            </button>
                            <div class="shrink-0 text-right">
                                <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Level</div>
                                <div class="mt-1 text-sm font-semibold">
                                    <x-severity-text :value="$report->level" :colors="[
                    'Critical' => 'text-[#c14545]',
                    'Severe' => 'text-[#b98967]',
                ]" default="text-[#c59b4a]" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Region</div>
                                <div class="mt-1 text-xs text-[#8f826b]">{{ $report->region->name ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Kingdom</div>
                                <div class="mt-1 text-xs text-[#8f826b]">{{ $report->kingdom->name ?? 'Unconfirmed' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Status</div>
                                <div class="mt-1 text-xs text-[#8f826b]">{{ $report->status }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Sightings</div>
                                <div class="mt-1 text-xs text-[#8f826b]">{{ $report->sightings }}</div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-end gap-5 border-t border-[#2c2922]/60 pt-4">
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
                    <span class="h-px w-8 bg-[#806337]"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-[#a17e43]">{{ $selected->report_number }}</span>
                </div>

                <h2 class="font-serif text-3xl text-[#e8dfca]">{{ $selected->title }}</h2>
                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-[#806337]">{{ $selected->type }}</p>

                <p class="mt-5 text-sm leading-7 text-[#8f826b]">{{ $selected->description ?? 'No further details on file.' }}
                </p>

                <div class="mt-7 grid grid-cols-2 gap-5 border-t border-[#2c2922] pt-6 text-xs">
                    <x-detail-item label="Region">{{ $selected->region->name ?? '—' }}</x-detail-item>
                    <x-detail-item label="Kingdom">{{ $selected->kingdom->name ?? 'Unconfirmed' }}</x-detail-item>
                    <div>
                        <p class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Level</p>
                        <p class="mt-1 font-semibold">
                            <x-severity-text :value="$selected->level" default="text-[#cdbd9e]" :colors="[
                        'Critical' => 'text-[#c14545]',
                        'Severe' => 'text-[#b98967]',
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
                    <span class="h-px w-8 bg-[#c14545]/60"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-[#c14545]">Strike from the Log</span>
                </div>

                <h2 class="font-serif text-2xl text-[#e8dfca]">Remove {{ $selected->title }}?</h2>
                <p class="mt-3 text-sm leading-6 text-[#8f826b]">
                    This permanently erases report <span class="text-[#cdbd9e]">{{ $selected->report_number }}</span> from the
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
                <h2 class="mb-6 font-serif text-2xl text-[#e8dfca]">
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
                            <p class="mt-1.5 text-[10px] text-[#c14545]">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-relation-picker label="Kingdom" search-model="kingdomFormSearch" placeholder="Search kingdoms..."
                                :options="$this->kingdomFormResults" :selected-id="$kingdomId" :selected-name="$kingdomName"
                                select-action="selectKingdomForm" clear-action="clearKingdomForm" clear-label="Unconfirmed"
                                option-label-key="label" />
                            @error('kingdomId')
                            <p class="mt-1.5 text-[10px] text-[#c14545]">{{ $message }}</p> @enderror
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
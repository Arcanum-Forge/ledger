<div>

    <x-flash-message />

    <x-page-header eyebrow="The Powers" title="Factions">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Record Faction</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    <x-summary-cards>
        <x-summary-card label="Factions Shown">
            <p class="font-serif text-3xl text-[#d8c8a8]">{{ $summary['total'] }}</p>
        </x-summary-card>
        <x-summary-card label="Average Influence">
            <p class="font-serif text-3xl text-[#d8c8a8]">{{ $summary['avgInfluence'] }}</p>
        </x-summary-card>
        <x-summary-card label="Most Influential" :last="true">
            <p class="font-serif text-lg text-[#b98967]">
                {{ $summary['mostInfluential']?->name ?? '—' }}
                @if ($summary['mostInfluential'])
                    <span class="text-sm text-[#756d5e]">({{ $summary['mostInfluential']->influence }})</span>
                @endif
            </p>
        </x-summary-card>
    </x-summary-cards>

    <x-filter-panel :active="$search || $alignmentFilter || $kingdomFilter || $leaderFilter || $minInfluence > 0 || $maxInfluence < 100">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-text-input model="search" label="Search" placeholder="Name, title, leader..." variant="filter"
                :span="true" />

            <x-select-input model="alignmentFilter" label="Alignment" :options="$alignments" variant="filter"
                placeholder="Any" />

            <x-relation-picker label="Headquarters" search-model="kingdomFilterSearch" placeholder="Search kingdoms..."
                :options="$this->kingdomFilterResults" :selected-id="$kingdomFilter" :selected-name="$kingdomFilterName"
                select-action="selectKingdomFilter" clear-action="clearKingdomFilter" />

            <x-relation-picker label="Leader" search-model="leaderFilterSearch" placeholder="Search leaders..."
                :options="$this->leaderFilterResults" :selected-id="$leaderFilter" :selected-name="$leaderFilterName"
                select-action="selectLeaderFilter" clear-action="clearLeaderFilter" />

            <x-range-filter-field label="Influence" min-model="minInfluence" max-model="maxInfluence"
                :min-value="$minInfluence" :max-value="$maxInfluence" />
        </div>
    </x-filter-panel>

    {{-- Table --}}
    <div class="border border-[#2c2922] bg-[#151310]">
        {{-- Desktop / Tablet --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[800px] text-left">
                <thead>
                    <tr class="border-b border-[#2c2922] text-[8px] uppercase tracking-[0.2em] text-[#625744]">
                        @foreach (['name' => 'Name', 'alignment' => 'Alignment', 'influence' => 'Influence', 'status' => 'Status'] as $col => $label)
                            <x-sortable-th :column="$col" :label="$label" :sort-by="$sortBy"
                                :sort-direction="$sortDirection" />
                        @endforeach

                        <th class="whitespace-nowrap px-5 py-4">Headquarters</th>
                        <th class="whitespace-nowrap px-5 py-4">Leader</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#2c2922]/60">
                    @forelse ($factions as $faction)
                        <tr class="transition hover:bg-[#191611]">
                            <td class="px-5 py-4">
                                <button wire:click="openView('{{ $faction->slug }}')" class="text-left">
                                    <div class="font-serif text-sm text-[#ddd2bb]">{{ $faction->name }}</div>
                                    <div class="text-[9px] uppercase tracking-[0.15em] text-[#625744]">{{ $faction->title }}
                                    </div>
                                </button>
                            </td>

                            <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em] text-[#8f826b]">
                                {{ $faction->alignment }}</td>
                            <td class="px-5 py-4 text-sm font-semibold text-[#d8c8a8]">{{ $faction->influence }}</td>
                            <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $faction->status }}</td>
                            <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $faction->kingdom->name ?? 'Unknown' }}</td>
                            <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $faction->leader->name ?? 'Unknown' }}</td>

                            <td class="px-5 py-4">
                                <x-row-actions :id="$faction->slug" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <x-empty-state title="No factions recorded." hint="Begin by charting the first power."
                                    :filtered="$search || $alignmentFilter || $kingdomFilter || $leaderFilter || $minInfluence > 0 || $maxInfluence < 100"
                                    filtered-title="No factions match these filters."
                                    filtered-hint="to see the full board." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="divide-y divide-[#2c2922]/60 md:hidden">
            @forelse ($factions as $faction)
                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <button wire:click="openView('{{ $faction->slug }}')" class="min-w-0 text-left">
                            <div class="font-serif text-base text-[#ddd2bb]">{{ $faction->name }}</div>
                            <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-[#625744]">{{ $faction->title }}
                            </div>
                        </button>
                        <div class="shrink-0 text-right">
                            <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Influence</div>
                            <div class="mt-1 text-sm font-semibold text-[#d8c8a8]">{{ $faction->influence }}</div>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Headquarters</div>
                            <div class="mt-1 text-xs text-[#8f826b]">{{ $faction->kingdom->name ?? 'Unknown' }}</div>
                        </div>
                        <div>
                            <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Leader</div>
                            <div class="mt-1 text-xs text-[#8f826b]">{{ $faction->leader->name ?? 'Unknown' }}</div>
                        </div>
                        <div>
                            <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Status</div>
                            <div class="mt-1 text-xs text-[#8f826b]">{{ $faction->status }}</div>
                        </div>
                        <div>
                            <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Alignment</div>
                            <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-[#8f826b]">
                                {{ $faction->alignment }}</div>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center justify-end gap-5 border-t border-[#2c2922]/60 pt-4">
                        <x-row-actions :id="$faction->slug" />
                    </div>
                </div>
            @empty
                <div class="px-5 py-16 text-center">
                    <x-empty-state title="No factions recorded." hint="Begin by charting the first power."
                        :filtered="$search || $alignmentFilter || $kingdomFilter || $leaderFilter || $minInfluence > 0 || $maxInfluence < 100" filtered-title="No factions match these filters."
                        filtered-hint="to see the full board." />
                </div>
            @endforelse
        </div>
    </div>

    <x-cursor-pagination :paginator="$factions" :total="$summary['total']" />

    <x-modal-shell>
        @if ($showModal)
            @if ($modalMode === 'view' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-[#806337]"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-[#a17e43]">{{ $selected->type }}</span>
                </div>

                <h2 class="font-serif text-3xl text-[#e8dfca]">{{ $selected->name }}</h2>
                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-[#806337]">{{ $selected->title }}</p>
                <p class="mt-5 text-sm leading-7 text-[#8f826b]">{{ $selected->description ?? 'No description recorded.' }}</p>

                <div class="mt-7 grid grid-cols-2 gap-5 border-t border-[#2c2922] pt-6 text-xs">
                    <x-detail-item label="Leader">{{ $selected->leader->name ?? 'Unknown' }}</x-detail-item>
                    <x-detail-item label="Headquarters">{{ $selected->kingdom->name ?? 'Unknown' }}</x-detail-item>
                    <x-detail-item label="Members">{{ $selected->members ?? 'Unconfirmed' }}</x-detail-item>
                    <x-detail-item label="Alignment">{{ $selected->alignment }}</x-detail-item>
                    <x-detail-item label="Influence"
                        class="font-semibold text-[#d8c8a8]">{{ $selected->influence }}</x-detail-item>
                    <x-detail-item label="Status">{{ $selected->status }}</x-detail-item>
                </div>

                @if ($selected->status_description)
                    <div class="mt-5 border-t border-[#2c2922] pt-5">
                        <p class="mb-1.5 text-[9px] uppercase tracking-[0.2em] text-[#625744]">Latest Report</p>
                        <p class="text-sm leading-7 text-[#8f826b]">{{ $selected->status_description }}</p>
                    </div>
                @endif

                <div class="mt-8 flex gap-3">
                    <x-btn-secondary wire:click="closeModal" class="flex-1">Close</x-btn-secondary>
                    <x-btn-primary wire:click="switchToEdit" class="flex-1">Edit</x-btn-primary>
                </div>

            @elseif ($modalMode === 'delete' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-[#c14545]/60"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-[#c14545]">Strike from the Record</span>
                </div>

                <h2 class="font-serif text-2xl text-[#e8dfca]">Remove {{ $selected->name }}?</h2>
                <p class="mt-3 text-sm leading-6 text-[#8f826b]">
                    This permanently erases <span class="text-[#cdbd9e]">{{ $selected->name }}</span>,
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
                <h2 class="mb-6 font-serif text-2xl text-[#e8dfca]">
                    {{ $modalMode === 'edit' ? 'Amend Record' : 'Record a New Faction' }}
                </h2>

                <form wire:submit="save" class="space-y-4">
                    <x-text-input model="name" label="Name" />
                    <x-text-input model="title" label="Title" placeholder="e.g. Order of the Sacred Shield" />
                    <x-textarea-input model="description" label="Description" :rows="3" />

                    <div class="grid grid-cols-2 gap-4">
                        <x-select-input model="type" label="Type" :options="$types" />

                        <div>
                            <x-relation-picker label="Leader" search-model="leaderFormSearch" placeholder="Search leaders..."
                                :options="$this->leaderFormResults" :selected-id="$leaderId" :selected-name="$leaderName"
                                select-action="selectLeaderForm" clear-action="clearLeaderForm" clear-label="Unknown"
                                option-label-key="label" />
                            @error('leaderId')
                            <p class="mt-1.5 text-[10px] text-[#c14545]">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-relation-picker label="Headquarters" search-model="kingdomFormSearch"
                                placeholder="Search kingdoms..." :options="$this->kingdomFormResults" :selected-id="$kingdomId"
                                :selected-name="$kingdomName" select-action="selectKingdomForm" clear-action="clearKingdomForm"
                                clear-label="Unknown" option-label-key="label" />
                            @error('kingdomId')
                            <p class="mt-1.5 text-[10px] text-[#c14545]">{{ $message }}</p> @enderror
                        </div>
                        <x-text-input model="members" label="Members" placeholder="e.g. 8,400" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-select-input model="alignment" label="Alignment" :options="$alignments" />
                        <x-range-field model="influence" label="Influence" />
                    </div>

                    <x-select-input model="status" label="Status" :options="$statuses" />

                    <x-textarea-input model="statusDescription" label="Status Report" :rows="2" />

                    <div class="flex gap-3 pt-2">
                        <x-btn-secondary type="button" wire:click="closeModal" class="flex-1">Cancel</x-btn-secondary>
                        <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save" class="flex-1">
                            <span wire:loading.remove
                                wire:target="save">{{ $modalMode === 'edit' ? 'Save Changes' : 'Record Faction' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </x-btn-primary>
                    </div>
                </form>
            @endif
        @endif
    </x-modal-shell>
</div>
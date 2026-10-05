{{-- factions/index.blade.php --}}
<div>

    @island(name: 'flash', always: true)
        <div>
            <x-flash-message />
        </div>
    @endisland

    <x-page-header eyebrow="The Powers" title="Factions">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Record Faction</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    @island(name: 'summary', lazy: true, always: true)
        @placeholder
            <x-summary-cards>
                <x-summary-card label="Factions Shown">
                    <div class="h-9 w-14 animate-pulse bg-line/60"></div>
                </x-summary-card>
                <x-summary-card label="Average Influence">
                    <div class="h-9 w-14 animate-pulse bg-line/60"></div>
                </x-summary-card>
                <x-summary-card label="Most Influential" :last="true">
                    <div class="h-7 w-32 animate-pulse bg-line/60"></div>
                </x-summary-card>
            </x-summary-cards>
        @endplaceholder

        @php
            $summary = $this->summary
        @endphp

        <x-summary-cards>
            <x-summary-card label="Factions Shown">
                <p class="font-serif text-3xl text-parchment">{{ $summary['total'] }}</p>
            </x-summary-card>
            <x-summary-card label="Average Influence">
                <p class="font-serif text-3xl text-parchment">{{ $summary['avgInfluence'] }}</p>
            </x-summary-card>
            <x-summary-card label="Most Influential" :last="true">
                <p class="font-serif text-lg text-gold">
                    {{ $summary['mostInfluential']?->name ?? '—' }}
                    @if ($summary['mostInfluential'])
                        <span class="text-sm text-faint">({{ $summary['mostInfluential']->influence }})</span>
                    @endif
                </p>
            </x-summary-card>
        </x-summary-cards>
    @endisland

    <x-filter-panel :active="$this->filtersActive">
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

    @island(name: 'table', lazy: true, always: true)
        @placeholder
            <div class="border border-line bg-card">
                <div class="min-h-[28rem] animate-pulse divide-y divide-line/60">
                    <div class="flex gap-6 px-5 py-4">
                        <div class="h-2.5 w-24 bg-line/60"></div>
                        <div class="h-2.5 w-16 bg-line/60"></div>
                        <div class="h-2.5 w-16 bg-line/60"></div>
                        <div class="h-2.5 w-20 bg-line/60"></div>
                    </div>
                    @foreach (range(1, 6) as $i)
                        <div class="flex items-center gap-6 px-5 py-5">
                            <div class="h-3.5 w-40 bg-line/60"></div>
                            <div class="h-3 w-20 bg-line/40"></div>
                            <div class="h-3.5 w-8 bg-line/60"></div>
                            <div class="h-3 w-16 bg-line/40"></div>
                            <div class="h-3 flex-1 bg-line/40"></div>
                            <div class="h-3 w-24 bg-line/40"></div>
                            <div class="h-3 w-20 bg-line/60"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endplaceholder

        @php
            $factions = $this->factions
        @endphp

        <div class="border border-line bg-card">
            {{-- Desktop / Tablet --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[800px] text-left">
                    <thead>
                        <tr class="border-b border-line text-[8px] uppercase tracking-[0.2em] text-faint">
                            @foreach (['name' => 'Name', 'alignment' => 'Alignment', 'influence' => 'Influence', 'status' => 'Status'] as $col => $label)
                                <x-sortable-th :column="$col" :label="$label" :sort-by="$this->sortBy"
                                    :sort-direction="$this->sortDirection" />
                            @endforeach

                            <th class="whitespace-nowrap px-5 py-4">Headquarters</th>
                            <th class="whitespace-nowrap px-5 py-4">Leader</th>
                            <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-line/60">
                        @forelse ($factions as $faction)
                            <tr class="transition hover:bg-card-hover">
                                <td class="px-5 py-4">
                                    {{-- wire:island="modal": the modal lives in its own island, so scope the update there --}}
                                    <button wire:click="openView('{{ $faction->slug }}')" wire:island="modal"
                                        class="text-left">
                                        <div class="font-serif text-sm text-parchment">{{ $faction->name }}</div>
                                        <div class="text-[9px] uppercase tracking-[0.15em] text-faint">
                                            {{ $faction->title }}
                                        </div>
                                    </button>
                                </td>

                                <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em] text-muted">
                                    {{ $faction->alignment }}
                                </td>
                                <td class="px-5 py-4 text-sm font-semibold text-parchment">{{ $faction->influence }}</td>
                                <td class="px-5 py-4 text-xs text-muted">{{ $faction->status }}</td>
                                <td class="px-5 py-4 text-xs text-muted">{{ $faction->kingdom->name ?? 'Unknown' }}</td>
                                <td class="px-5 py-4 text-xs text-muted">{{ $faction->leader->name ?? 'Unknown' }}</td>

                                <td class="px-5 py-4">
                                    <x-row-actions :id="$faction->slug" island="modal" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <x-empty-state title="No factions recorded."
                                        hint="Begin by charting the first power." :filtered="$this->filtersActive"
                                        filtered-title="No factions match these filters."
                                        filtered-hint="to see the full board." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="divide-y divide-line/60 md:hidden">
                @forelse ($factions as $faction)
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <button wire:click="openView('{{ $faction->slug }}')" wire:island="modal"
                                class="min-w-0 text-left">
                                <div class="font-serif text-base text-parchment">{{ $faction->name }}</div>
                                <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-faint">
                                    {{ $faction->title }}
                                </div>
                            </button>
                            <div class="shrink-0 text-right">
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Influence</div>
                                <div class="mt-1 text-sm font-semibold text-parchment">{{ $faction->influence }}</div>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Headquarters</div>
                                <div class="mt-1 text-xs text-muted">{{ $faction->kingdom->name ?? 'Unknown' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Leader</div>
                                <div class="mt-1 text-xs text-muted">{{ $faction->leader->name ?? 'Unknown' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Status</div>
                                <div class="mt-1 text-xs text-muted">{{ $faction->status }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Alignment</div>
                                <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-muted">
                                    {{ $faction->alignment }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-end gap-5 border-t border-line/60 pt-4">
                            <x-row-actions :id="$faction->slug" island="modal" />
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-16 text-center">
                        <x-empty-state title="No factions recorded." hint="Begin by charting the first power."
                            :filtered="$this->filtersActive" filtered-title="No factions match these filters."
                            filtered-hint="to see the full board." />
                    </div>
                @endforelse
            </div>
        </div>

        <x-cursor-pagination :paginator="$factions" :total="$this->total" />
    @endisland

    {{-- Modal: always re-renders with the root (openCreate, backdrop/Esc close) and is targeted by row actions --}}
    @island(name: 'modal', always: true)
        <x-modal-shell>
            @if ($this->showModal)
                @if ($this->modalMode === 'view' && $this->selected)
                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-8 bg-line"></span>
                        <span
                            class="text-[9px] uppercase tracking-[0.28em] text-gold-dim">{{ $this->selected->type }}</span>
                    </div>

                    <h2 class="font-serif text-3xl text-parchment-bright">{{ $this->selected->name }}</h2>
                    <p class="mt-1 text-xs uppercase tracking-[0.2em] text-line">{{ $this->selected->title }}</p>
                    <p class="mt-5 text-sm leading-7 text-muted">
                        {{ $this->selected->description ?? 'No description recorded.' }}</p>

                    <div class="mt-7 grid grid-cols-2 gap-5 border-t border-line pt-6 text-xs">
                        <x-detail-item label="Leader">{{ $this->selected->leader->name ?? 'Unknown' }}</x-detail-item>
                        <x-detail-item
                            label="Headquarters">{{ $this->selected->kingdom->name ?? 'Unknown' }}</x-detail-item>
                        <x-detail-item label="Members">{{ $this->selected->members ?? 'Unconfirmed' }}</x-detail-item>
                        <x-detail-item label="Alignment">{{ $this->selected->alignment }}</x-detail-item>
                        <x-detail-item label="Influence"
                            class="font-semibold text-parchment">{{ $this->selected->influence }}</x-detail-item>
                        <x-detail-item label="Status">{{ $this->selected->status }}</x-detail-item>
                    </div>

                    @if ($this->selected->status_description)
                        <div class="mt-5 border-t border-line pt-5">
                            <p class="mb-1.5 text-[9px] uppercase tracking-[0.2em] text-faint">Latest Report</p>
                            <p class="text-sm leading-7 text-muted">{{ $this->selected->status_description }}</p>
                        </div>
                    @endif

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
                        {{ $this->modalMode === 'edit' ? 'Amend Record' : 'Record a New Faction' }}
                    </h2>

                    <form wire:submit="save" class="space-y-4">
                        <x-text-input model="name" label="Name" />
                        <x-text-input model="title" label="Title" placeholder="e.g. Order of the Sacred Shield" />
                        <x-textarea-input model="description" label="Description" :rows="3" />

                        <div class="grid grid-cols-2 gap-4">
                            <x-select-input model="type" label="Type" :options="$this->types" />

                            <div>
                                <x-relation-picker label="Leader" search-model="leaderFormSearch"
                                    placeholder="Search leaders..." :options="$this->leaderFormResults"
                                    :selected-id="$this->leaderId" :selected-name="$this->leaderName"
                                    select-action="selectLeaderForm" clear-action="clearLeaderForm"
                                    clear-label="Unknown" option-label-key="label" />
                                @error('leaderId')
                                    <p class="mt-1.5 text-[10px] text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-relation-picker label="Headquarters" search-model="kingdomFormSearch"
                                    placeholder="Search kingdoms..." :options="$this->kingdomFormResults"
                                    :selected-id="$this->kingdomId" :selected-name="$this->kingdomName"
                                    select-action="selectKingdomForm" clear-action="clearKingdomForm"
                                    clear-label="Unknown" option-label-key="label" />
                                @error('kingdomId')
                                    <p class="mt-1.5 text-[10px] text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <x-text-input model="members" label="Members" placeholder="e.g. 8,400" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <x-select-input model="alignment" label="Alignment" :options="$this->alignments" />
                            <x-range-field model="influence" label="Influence" />
                        </div>

                        <x-select-input model="status" label="Status" :options="$this->statuses" />

                        <x-textarea-input model="statusDescription" label="Status Report" :rows="2" />

                        <div class="flex gap-3 pt-2">
                            <x-btn-secondary type="button" wire:click="closeModal"
                                class="flex-1">Cancel</x-btn-secondary>
                            <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="flex-1">
                                <span wire:loading.remove
                                    wire:target="save">{{ $this->modalMode === 'edit' ? 'Save Changes' : 'Record Faction' }}</span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </x-btn-primary>
                        </div>
                    </form>
                @endif
            @endif
        </x-modal-shell>
    @endisland
</div>
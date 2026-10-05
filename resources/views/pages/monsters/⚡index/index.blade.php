{{-- monsters/index.blade.php --}}
<div>

    @island(name: 'flash', always: true)
        <div>
            <x-flash-message />
        </div>
    @endisland

    <x-page-header eyebrow="The Wilds" title="Monsters">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Log Sighting</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    @island(name: 'summary', lazy: true, always: true)
        @placeholder
            <x-summary-cards>
                <x-summary-card label="Monsters Shown">
                    <div class="h-9 w-14 animate-pulse bg-line/60"></div>
                </x-summary-card>
                <x-summary-card label="Average Sightings">
                    <div class="h-9 w-14 animate-pulse bg-line/60"></div>
                </x-summary-card>
                <x-summary-card label="Most Threatening" :last="true">
                    <div class="h-7 w-32 animate-pulse bg-line/60"></div>
                </x-summary-card>
            </x-summary-cards>
        @endplaceholder

        @php
            $summary = $this->summary
        @endphp

        <x-summary-cards>
            <x-summary-card label="Monsters Shown">
                <p class="font-serif text-3xl text-parchment">{{ $summary['total'] }}</p>
            </x-summary-card>
            <x-summary-card label="Average Sightings">
                <p class="font-serif text-3xl text-parchment">{{ $summary['avgSightings'] }}</p>
            </x-summary-card>
            <x-summary-card label="Most Threatening" :last="true">
                <p class="font-serif text-lg text-gold">
                    {{ $summary['mostThreatening']?->name ?? '—' }}
                    @if ($summary['mostThreatening'])
                        <span class="text-sm text-faint">({{ $summary['mostThreatening']->threat }})</span>
                    @endif
                </p>
            </x-summary-card>
        </x-summary-cards>
    @endisland

    <x-filter-panel :active="$this->filtersActive">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <x-text-input model="search" label="Search" placeholder="Name, description..." variant="filter"
                :span="true" />

            <x-select-input model="classificationFilter" label="Classification" :options="$classifications"
                variant="filter" placeholder="Any" />

            <x-select-input model="habitatFilter" label="Habitat" :options="$habitats" variant="filter"
                placeholder="Any" />

            <x-select-input model="threatFilter" label="Threat" :options="$threats" variant="filter"
                placeholder="Any" />

            <x-relation-picker label="Kingdom" search-model="kingdomFilterSearch" placeholder="Search kingdoms..."
                :options="$this->kingdomFilterResults" :selected-id="$kingdomFilter" :selected-name="$kingdomFilterName"
                select-action="selectKingdomFilter" clear-action="clearKingdomFilter" />

            <x-range-filter-field label="Sightings" min-model="minSightings" max-model="maxSightings"
                :min-value="$minSightings" :max-value="$maxSightings" :max="150" />
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
                        <div class="h-2.5 w-16 bg-line/60"></div>
                        <div class="h-2.5 w-20 bg-line/60"></div>
                    </div>
                    @foreach (range(1, 6) as $i)
                        <div class="flex items-center gap-6 px-5 py-5">
                            <div class="h-3.5 w-36 bg-line/60"></div>
                            <div class="h-3 w-20 bg-line/40"></div>
                            <div class="h-3 w-20 bg-line/40"></div>
                            <div class="h-3 w-14 bg-line/40"></div>
                            <div class="h-3.5 w-8 bg-line/60"></div>
                            <div class="h-3 w-16 bg-line/40"></div>
                            <div class="h-3 flex-1 bg-line/40"></div>
                            <div class="h-3 w-20 bg-line/60"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endplaceholder

        @php
            $monsters = $this->monsters
        @endphp

        <div class="border border-line bg-card">
            {{-- Desktop / Tablet --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[900px] text-left">
                    <thead>
                        <tr class="border-b border-line text-[8px] uppercase tracking-[0.2em] text-faint">
                            @foreach (['name' => 'Name', 'classification' => 'Classification', 'habitat' => 'Habitat', 'threat' => 'Threat', 'sightings' => 'Sightings', 'status' => 'Status'] as $col => $label)
                                <x-sortable-th :column="$col" :label="$label" :sort-by="$this->sortBy"
                                    :sort-direction="$this->sortDirection" />
                            @endforeach

                            <th class="whitespace-nowrap px-5 py-4">Kingdom</th>
                            <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-line/60">
                        @forelse ($monsters as $monster)
                            <tr class="transition hover:bg-card-hover">
                                <td class="px-5 py-4">
                                    {{-- wire:island="modal": the modal lives in its own island, so scope the update there --}}
                                    <button wire:click="openView('{{ $monster->slug }}')" wire:island="modal"
                                        class="text-left">
                                        <div class="font-serif text-sm text-parchment">{{ $monster->name }}</div>
                                    </button>
                                </td>
                                <td class="px-5 py-4 text-xs text-muted">{{ $monster->classification }}</td>
                                <td class="px-5 py-4 text-xs text-muted">{{ $monster->habitat }}</td>
                                <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em]">
                                    <x-severity-text :value="$monster->threat" :colors="[
                                        'Extreme' => 'text-danger',
                                        'High' => 'text-gold',
                                        'Moderate' => 'text-gold',
                                    ]" />
                                </td>
                                <td class="px-5 py-4 text-sm font-semibold text-parchment">{{ $monster->sightings }}</td>
                                <td class="px-5 py-4 text-xs text-muted">{{ $monster->status }}</td>
                                <td class="px-5 py-4 text-xs text-muted">
                                    {{ $monster->kingdom->name ?? 'Unconfirmed' }}</td>
                                <td class="px-5 py-4">
                                    <x-row-actions :id="$monster->slug" island="modal" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <x-empty-state title="No sightings recorded."
                                        hint="Begin by logging the first encounter." :filtered="$this->filtersActive"
                                        filtered-title="No monsters match these filters."
                                        filtered-hint="to see the full bestiary." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="divide-y divide-line/60 md:hidden">
                @forelse ($monsters as $monster)
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <button wire:click="openView('{{ $monster->slug }}')" wire:island="modal"
                                class="min-w-0 text-left">
                                <div class="font-serif text-base text-parchment">{{ $monster->name }}</div>
                                <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-faint">
                                    {{ $monster->classification }}
                                </div>
                            </button>
                            <div class="shrink-0 text-right">
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Threat</div>
                                <div class="mt-1 text-sm font-semibold">
                                    <x-severity-text :value="$monster->threat" :colors="[
                                        'Extreme' => 'text-danger',
                                        'High' => 'text-gold',
                                        'Moderate' => 'text-gold',
                                    ]" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Habitat</div>
                                <div class="mt-1 text-xs text-muted">{{ $monster->habitat }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Sightings</div>
                                <div class="mt-1 text-xs text-muted">{{ $monster->sightings }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Kingdom</div>
                                <div class="mt-1 text-xs text-muted">{{ $monster->kingdom->name ?? 'Unconfirmed' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Status</div>
                                <div class="mt-1 text-xs text-muted">{{ $monster->status }}</div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-end gap-5 border-t border-line/60 pt-4">
                            <x-row-actions :id="$monster->slug" island="modal" />
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-16 text-center">
                        <x-empty-state title="No sightings recorded." hint="Begin by logging the first encounter."
                            :filtered="$this->filtersActive" filtered-title="No monsters match these filters."
                            filtered-hint="to see the full bestiary." />
                    </div>
                @endforelse
            </div>
        </div>

        <x-cursor-pagination :paginator="$monsters" :total="$this->total" />
    @endisland

    @island(name: 'modal', always: true)
        <x-modal-shell>
            @if ($this->showModal)
                @if ($this->modalMode === 'view' && $this->selected)
                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-8 bg-line"></span>
                        <span
                            class="text-[9px] uppercase tracking-[0.28em] text-gold-dim">{{ $this->selected->classification }}</span>
                    </div>

                    <h2 class="font-serif text-3xl text-parchment-bright">{{ $this->selected->name }}</h2>
                    <p class="mt-5 text-sm leading-7 text-muted">
                        {{ $this->selected->description ?? 'No records on file.' }}</p>

                    <div class="mt-7 grid grid-cols-2 gap-5 border-t border-line pt-6 text-xs">
                        <x-detail-item label="Habitat">{{ $this->selected->habitat }}</x-detail-item>
                        <x-detail-item
                            label="Kingdom">{{ $this->selected->kingdom->name ?? 'Unconfirmed' }}</x-detail-item>
                        <div>
                            <p class="text-[8px] uppercase tracking-[0.15em] text-faint">Threat</p>
                            <p class="mt-1 font-semibold">
                                <x-severity-text :value="$this->selected->threat" default="text-parchment-dim"
                                    :colors="[
                                        'Extreme' => 'text-danger',
                                        'High' => 'text-gold',
                                        'Moderate' => 'text-gold',
                                    ]" />
                            </p>
                        </div>
                        <x-detail-item label="Sightings">{{ $this->selected->sightings }}</x-detail-item>
                        <x-detail-item label="Status">{{ $this->selected->status }}</x-detail-item>
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
                        This permanently erases the record of <span
                            class="text-parchment-dim">{{ $this->selected->name }}</span> from the
                        bestiary. This cannot be undone.
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
                        {{ $this->modalMode === 'edit' ? 'Amend Record' : 'Log a New Sighting' }}
                    </h2>

                    <form wire:submit="save" class="space-y-4">
                        <x-text-input model="name" label="Name" />
                        <x-textarea-input model="description" label="Description" :rows="3" />

                        <div class="grid grid-cols-2 gap-4">
                            <x-select-input model="classification" label="Classification"
                                :options="$this->classifications" />
                            <x-select-input model="habitat" label="Habitat" :options="$this->habitats" />
                        </div>

                        <div>
                            <x-relation-picker label="Kingdom" search-model="kingdomFormSearch"
                                placeholder="Search kingdoms..." :options="$this->kingdomFormResults"
                                :selected-id="$this->kingdomId" :selected-name="$this->kingdomName"
                                select-action="selectKingdomForm" clear-action="clearKingdomForm"
                                clear-label="Unconfirmed" option-label-key="label" />
                            @error('kingdomId')
                                <p class="mt-1.5 text-[10px] text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <x-select-input model="threat" label="Threat" :options="$this->threats" />
                            <x-text-input model="sightings" label="Sightings" type="number" min="0" />
                        </div>

                        <x-select-input model="status" label="Status" :options="$this->statuses" />

                        <div class="flex gap-3 pt-2">
                            <x-btn-secondary type="button" wire:click="closeModal"
                                class="flex-1">Cancel</x-btn-secondary>
                            <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="flex-1">
                                <span wire:loading.remove
                                    wire:target="save">{{ $this->modalMode === 'edit' ? 'Save Changes' : 'Log Sighting' }}</span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </x-btn-primary>
                        </div>
                    </form>
                @endif
            @endif
        </x-modal-shell>
    @endisland
</div>
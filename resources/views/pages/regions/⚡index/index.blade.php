{{-- regions/index.blade.php --}}
<div>

    @island(name: 'flash', always: true)
        <div>
            <x-flash-message />
        </div>
    @endisland

    <x-page-header eyebrow="The World" title="Regions">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Chart Region</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    @island(name: 'summary', lazy: true, always: true)
        @placeholder
            <x-summary-cards>
                <x-summary-card label="Regions Shown">
                    <div class="h-9 w-14 animate-pulse bg-line/60"></div>
                </x-summary-card>
                <x-summary-card label="Avg. Kingdoms">
                    <div class="h-9 w-14 animate-pulse bg-line/60"></div>
                </x-summary-card>
                <x-summary-card label="Most Populous" :last="true">
                    <div class="h-7 w-32 animate-pulse bg-line/60"></div>
                </x-summary-card>
            </x-summary-cards>
        @endplaceholder

        @php
            $summary = $this->summary
        @endphp

        <x-summary-cards>
            <x-summary-card label="Regions Shown">
                <p class="font-serif text-3xl text-parchment">{{ $summary['total'] }}</p>
            </x-summary-card>
            <x-summary-card label="Avg. Kingdoms">
                <p class="font-serif text-3xl text-parchment">{{ $summary['avgKingdoms'] }}</p>
            </x-summary-card>
            <x-summary-card label="Most Populous" :last="true">
                <p class="font-serif text-lg text-gold">
                    {{ $summary['mostPopulous']?->name ?? '—' }}
                    @if ($summary['mostPopulous'])
                        <span class="text-sm text-faint">({{ $summary['mostPopulous']->kingdoms_count }})</span>
                    @endif
                </p>
            </x-summary-card>
        </x-summary-cards>
    @endisland

    <x-filter-panel :active="$this->filtersActive">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-text-input model="search" label="Search" placeholder="Name, description..." variant="filter" />

            <x-range-filter-field label="Kingdoms" min-model="minKingdoms" max-model="maxKingdoms"
                :min-value="$minKingdoms" :max-value="$maxKingdoms" />
        </div>
    </x-filter-panel>

    @island(name: 'table', lazy: true, always: true)
        @placeholder
            <div class="border border-line bg-card">
                <div class="min-h-[28rem] animate-pulse divide-y divide-line/60">
                    <div class="flex gap-6 px-5 py-4">
                        <div class="h-2.5 w-24 bg-line/60"></div>
                        <div class="h-2.5 w-16 bg-line/60"></div>
                        <div class="h-2.5 w-20 bg-line/60"></div>
                    </div>
                    @foreach (range(1, 6) as $i)
                        <div class="flex items-center gap-6 px-5 py-5">
                            <div class="h-3.5 w-40 bg-line/60"></div>
                            <div class="h-3.5 w-8 bg-line/60"></div>
                            <div class="h-3 flex-1 bg-line/40"></div>
                            <div class="h-3 w-20 bg-line/60"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endplaceholder

        @php
            $regions = $this->regions
        @endphp

        <div class="border border-line bg-card">
            {{-- Desktop / Tablet --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[700px] text-left">
                    <thead>
                        <tr class="border-b border-line text-[8px] uppercase tracking-[0.2em] text-faint">
                            @foreach (['name' => 'Name', 'kingdoms_count' => 'Kingdoms'] as $col => $label)
                                <x-sortable-th :column="$col" :label="$label" :sort-by="$this->sortBy"
                                    :sort-direction="$this->sortDirection" />
                            @endforeach

                            <th class="whitespace-nowrap px-5 py-4">Description</th>
                            <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-line/60">
                        @forelse ($regions as $region)
                            <tr class="transition hover:bg-card-hover">
                                <td class="px-5 py-4">
                                    {{-- wire:island="modal": the modal lives in its own island, so scope the update there --}}
                                    <button wire:click="openView('{{ $region->slug }}')" wire:island="modal"
                                        class="text-left">
                                        <div class="font-serif text-sm text-parchment">{{ $region->name }}</div>
                                    </button>
                                </td>

                                <td class="px-5 py-4 text-sm font-semibold text-parchment">{{ $region->kingdoms_count }}
                                </td>

                                <td class="max-w-md truncate px-5 py-4 text-xs text-muted">
                                    {{ $region->description ?? '—' }}
                                </td>

                                <td class="px-5 py-4">
                                    <x-row-actions :id="$region->slug" island="modal" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-16 text-center">
                                    <x-empty-state title="No regions charted."
                                        hint="Begin by mapping the first territory." :filtered="$this->filtersActive"
                                        filtered-title="No regions match these filters."
                                        filtered-hint="to see the full map." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="divide-y divide-line/60 md:hidden">
                @forelse ($regions as $region)
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <button wire:click="openView('{{ $region->slug }}')" wire:island="modal"
                                class="min-w-0 text-left">
                                <div class="font-serif text-base text-parchment">{{ $region->name }}</div>
                            </button>
                            <div class="shrink-0 text-right">
                                <div class="text-[8px] uppercase tracking-[0.15em] text-faint">Kingdoms</div>
                                <div class="mt-1 text-sm font-semibold text-parchment">{{ $region->kingdoms_count }}
                                </div>
                            </div>
                        </div>

                        <p class="mt-4 text-xs text-muted">{{ $region->description ?? '—' }}</p>

                        <div class="mt-5 flex items-center justify-end gap-5 border-t border-line/60 pt-4">
                            <x-row-actions :id="$region->slug" island="modal" />
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-16 text-center">
                        <x-empty-state title="No regions charted." hint="Begin by mapping the first territory."
                            :filtered="$this->filtersActive" filtered-title="No regions match these filters."
                            filtered-hint="to see the full map." />
                    </div>
                @endforelse
            </div>
        </div>

        <x-cursor-pagination :paginator="$regions" :total="$this->total" />
    @endisland

    @island(name: 'modal', always: true)
        <x-modal-shell>
            @if ($this->showModal)
                @if ($this->modalMode === 'view' && $this->selected)
                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-8 bg-line"></span>
                        <span class="text-[9px] uppercase tracking-[0.28em] text-gold-dim">Territory Record</span>
                    </div>

                    <h2 class="font-serif text-3xl text-parchment-bright">{{ $this->selected->name }}</h2>
                    <p class="mt-5 text-sm leading-7 text-muted">
                        {{ $this->selected->description ?? 'No description recorded.' }}</p>

                    <div class="mt-7 grid grid-cols-2 gap-5 border-t border-line pt-6 text-xs">
                        <x-detail-item label="Kingdoms"
                            class="font-semibold text-parchment">{{ $this->selected->kingdoms_count }}</x-detail-item>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <x-btn-secondary wire:click="closeModal" class="flex-1">Close</x-btn-secondary>
                        <x-btn-primary wire:click="switchToEdit" class="flex-1">Edit</x-btn-primary>
                    </div>

                @elseif ($this->modalMode === 'delete' && $this->selected)
                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-8 bg-danger/60"></span>
                        <span class="text-[9px] uppercase tracking-[0.28em] text-danger">Strike from the Map</span>
                    </div>

                    <h2 class="font-serif text-2xl text-parchment-bright">Remove {{ $this->selected->name }}?</h2>
                    <p class="mt-3 text-sm leading-6 text-muted">
                        Kingdoms tied to this region will remain, but lose their regional link. This cannot be undone.
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
                        {{ $this->modalMode === 'edit' ? 'Amend Territory' : 'Chart a New Region' }}
                    </h2>

                    <form wire:submit="save" class="space-y-4">
                        <x-text-input model="name" label="Name" />
                        <x-textarea-input model="description" label="Description" :rows="4" />

                        <div class="flex gap-3 pt-2">
                            <x-btn-secondary type="button" wire:click="closeModal"
                                class="flex-1">Cancel</x-btn-secondary>
                            <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="flex-1">
                                <span wire:loading.remove
                                    wire:target="save">{{ $this->modalMode === 'edit' ? 'Save Changes' : 'Chart Region' }}</span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </x-btn-primary>
                        </div>
                    </form>
                @endif
            @endif
        </x-modal-shell>
    @endisland
</div>
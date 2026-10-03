<div>

    <x-flash-message />

    <x-page-header eyebrow="The Powers" title="Leaders">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Record Leader</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    <x-summary-cards>
        <x-summary-card label="Leaders Shown">
            <p class="font-serif text-3xl text-[#d8c8a8]">{{ $summary['total'] }}</p>
        </x-summary-card>
        <x-summary-card label="Avg. Factions">
            <p class="font-serif text-3xl text-[#d8c8a8]">{{ $summary['avgFactions'] }}</p>
        </x-summary-card>
        <x-summary-card label="Most Factions Led" :last="true">
            <p class="font-serif text-lg text-[#b98967]">
                {{ $summary['mostFactions']?->name ?? '—' }}
                @if ($summary['mostFactions'])
                    <span class="text-sm text-[#756d5e]">({{ $summary['mostFactions']->factions_count }})</span>
                @endif
            </p>
        </x-summary-card>
    </x-summary-cards>

    <x-filter-panel :active="$search || $minFactions > 0 || $maxFactions < 100">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-text-input model="search" label="Search" placeholder="Name, bio, notes..." variant="filter" />

            <x-range-filter-field label="Factions" min-model="minFactions" max-model="maxFactions" :min-value="$minFactions" :max-value="$maxFactions" />
        </div>
    </x-filter-panel>

    {{-- Table --}}
    <div class="border border-[#2c2922] bg-[#151310]">
        {{-- Desktop / Tablet --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[700px] text-left">
                <thead>
                    <tr class="border-b border-[#2c2922] text-[8px] uppercase tracking-[0.2em] text-[#625744]">
                        @foreach (['name' => 'Name', 'factions_count' => 'Factions'] as $col => $label)
                            <x-sortable-th :column="$col" :label="$label" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                        @endforeach

                        <th class="whitespace-nowrap px-5 py-4">Bio</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#2c2922]/60">
                    @forelse ($leaders as $leader)
                        <tr class="transition hover:bg-[#191611]">
                            <td class="px-5 py-4">
                                <button wire:click="openView('{{ $leader->slug }}')" class="text-left">
                                    <div class="font-serif text-sm text-[#ddd2bb]">{{ $leader->name }}</div>
                                </button>
                            </td>

                            <td class="px-5 py-4 text-sm font-semibold text-[#d8c8a8]">{{ $leader->factions_count }}</td>

                            <td class="px-5 py-4 max-w-md truncate text-xs text-[#8f826b]">{{ $leader->bio ?? '—' }}</td>

                            <td class="px-5 py-4">
                                <x-row-actions :id="$leader->slug" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">
                                <x-empty-state
                                    title="No leaders recorded."
                                    hint="Begin by naming the first leader."
                                    :filtered="$search || $minFactions > 0 || $maxFactions < 100"
                                    filtered-title="No leaders match these filters."
                                    filtered-hint="to see the full roster."
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="divide-y divide-[#2c2922]/60 md:hidden">
            @forelse ($leaders as $leader)
                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <button wire:click="openView('{{ $leader->slug }}')" class="min-w-0 text-left">
                            <div class="font-serif text-base text-[#ddd2bb]">{{ $leader->name }}</div>
                        </button>
                        <div class="shrink-0 text-right">
                            <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Factions</div>
                            <div class="mt-1 text-sm font-semibold text-[#d8c8a8]">{{ $leader->factions_count }}</div>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-[#8f826b]">{{ $leader->bio ?? '—' }}</p>

                    <div class="mt-5 flex items-center justify-end gap-5 border-t border-[#2c2922]/60 pt-4">
                        <x-row-actions :id="$leader->slug" />
                    </div>
                </div>
            @empty
                <div class="px-5 py-16 text-center">
                    <x-empty-state
                        title="No leaders recorded."
                        hint="Begin by naming the first leader."
                        :filtered="$search || $minFactions > 0 || $maxFactions < 100"
                        filtered-title="No leaders match these filters."
                        filtered-hint="to see the full roster."
                    />
                </div>
            @endforelse
        </div>
    </div>

    <x-cursor-pagination :paginator="$leaders" :total="$summary['total']" />

    <x-modal-shell>
        @if ($showModal)
            @if ($modalMode === 'view' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-[#806337]"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-[#a17e43]">Leader Record</span>
                </div>

                <h2 class="font-serif text-3xl text-[#e8dfca]">{{ $selected->name }}</h2>
                <p class="mt-5 text-sm leading-7 text-[#8f826b]">{{ $selected->bio ?? 'No biography recorded.' }}</p>

                @if ($selected->notes)
                    <div class="mt-5 border-t border-[#2c2922] pt-5">
                        <p class="mb-1.5 text-[9px] uppercase tracking-[0.2em] text-[#625744]">Notes</p>
                        <p class="text-sm leading-7 text-[#8f826b]">{{ $selected->notes }}</p>
                    </div>
                @endif

                <div class="mt-7 grid grid-cols-2 gap-5 border-t border-[#2c2922] pt-6 text-xs">
                    <x-detail-item label="Factions" class="font-semibold text-[#d8c8a8]">{{ $selected->factions_count }}</x-detail-item>
                </div>

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
                    Factions led by this person will remain, but lose their leader link. This cannot be undone.
                </p>

                <div class="mt-8 flex gap-3">
                    <x-btn-secondary wire:click="closeModal" class="flex-1">Cancel</x-btn-secondary>
                    <x-btn-danger wire:click="confirmDelete" wire:loading.attr="disabled" wire:target="confirmDelete" class="flex-1">
                        <span wire:loading.remove wire:target="confirmDelete">Confirm Removal</span>
                        <span wire:loading wire:target="confirmDelete">Removing...</span>
                    </x-btn-danger>
                </div>

            @else
                <h2 class="mb-6 font-serif text-2xl text-[#e8dfca]">
                    {{ $modalMode === 'edit' ? 'Amend Record' : 'Record a New Leader' }}
                </h2>

                <form wire:submit="save" class="space-y-4">
                    <x-text-input model="name" label="Name" />
                    <x-textarea-input model="bio" label="Bio" :rows="3" />
                    <x-textarea-input model="notes" label="Notes" :rows="3" />

                    <div class="flex gap-3 pt-2">
                        <x-btn-secondary type="button" wire:click="closeModal" class="flex-1">Cancel</x-btn-secondary>
                        <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save" class="flex-1">
                            <span wire:loading.remove wire:target="save">{{ $modalMode === 'edit' ? 'Save Changes' : 'Record Leader' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </x-btn-primary>
                    </div>
                </form>
            @endif
        @endif
    </x-modal-shell>
</div>
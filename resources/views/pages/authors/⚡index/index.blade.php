<div>

    <x-flash-message />

    <x-page-header eyebrow="The Archive" title="Authors">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Record Author</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    <x-summary-cards>
        <x-summary-card label="Authors Shown">
            <p class="font-serif text-3xl text-parchment">{{ $summary['total'] }}</p>
        </x-summary-card>
        <x-summary-card label="Avg. Records">
            <p class="font-serif text-3xl text-parchment">{{ $summary['avgRecords'] }}</p>
        </x-summary-card>
        <x-summary-card label="Most Records Kept" :last="true">
            <p class="font-serif text-lg text-gold">
                {{ $summary['mostRecords']?->name ?? '—' }}
                @if ($summary['mostRecords'])
                    <span class="text-sm text-faint">({{ $summary['mostRecords']->records_count }})</span>
                @endif
            </p>
        </x-summary-card>
    </x-summary-cards>

    <x-filter-panel :active="$search || $minRecords > 0 || $maxRecords < 100">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-text-input model="search" label="Search" placeholder="Name, bio, notes..." variant="filter" />

            <x-range-filter-field label="Records" min-model="minRecords" max-model="maxRecords" :min-value="$minRecords"
                :max-value="$maxRecords" />
        </div>
    </x-filter-panel>

    {{-- Table --}}
    <div class="border border-line bg-card">
        {{-- Desktop / Tablet --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[700px] text-left">
                <thead>
                    <tr class="border-b border-line text-[10px] uppercase tracking-[0.2em] text-muted">
                        @foreach (['name' => 'Name', 'records_count' => 'Records'] as $col => $label)
                            <x-sortable-th :column="$col" :label="$label" :sort-by="$sortBy"
                                :sort-direction="$sortDirection" />
                        @endforeach

                        <th class="whitespace-nowrap px-5 py-4">Bio</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-line/60">
                    @forelse ($authors as $author)
                        <tr class="transition hover:bg-card-hover">
                            <td class="px-5 py-4">
                                <button wire:click="openView('{{ $author->slug }}')" class="text-left">
                                    <div class="font-serif text-sm text-parchment">{{ $author->name }}</div>
                                </button>
                            </td>

                            <td class="px-5 py-4 text-sm font-semibold text-parchment">{{ $author->records_count }}</td>

                            <td class="max-w-md truncate px-5 py-4 text-xs text-muted">{{ $author->bio ?? '—' }}</td>

                            <td class="px-5 py-4">
                                <x-row-actions :id="$author->slug" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">
                                <x-empty-state title="No authors recorded." hint="Begin by crediting the first source."
                                    :filtered="$search || $minRecords > 0 || $maxRecords < 100"
                                    filtered-title="No authors match these filters."
                                    filtered-hint="to see the full archive." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="divide-y divide-line/60 md:hidden">
            @forelse ($authors as $author)
                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <button wire:click="openView('{{ $author->slug }}')" class="min-w-0 text-left">
                            <div class="font-serif text-base text-parchment">{{ $author->name }}</div>
                        </button>
                        <div class="shrink-0 text-right">
                            <div class="text-[10px] uppercase tracking-[0.15em] text-faint">Records</div>
                            <div class="mt-1 text-sm font-semibold text-parchment">{{ $author->records_count }}</div>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-muted">{{ $author->bio ?? '—' }}</p>

                    <div class="mt-5 flex items-center justify-end gap-5 border-t border-line/60 pt-4">
                        <x-row-actions :id="$author->slug" />
                    </div>
                </div>
            @empty
                <div class="px-5 py-16 text-center">
                    <x-empty-state title="No authors recorded." hint="Begin by crediting the first source."
                        :filtered="$search || $minRecords > 0 || $maxRecords < 100"
                        filtered-title="No authors match these filters." filtered-hint="to see the full archive." />
                </div>
            @endforelse
        </div>
    </div>

    <x-cursor-pagination :paginator="$authors" :total="$summary['total']" />

    <x-modal-shell>
        @if ($showModal)
            @if ($modalMode === 'view' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-bronze"></span>
                    <span class="text-[10px] uppercase tracking-[0.28em] text-gold-dim">Author Record</span>
                </div>

                <h2 class="font-serif text-3xl text-parchment-bright">{{ $selected->name }}</h2>
                <p class="mt-5 text-sm leading-7 text-muted">{{ $selected->bio ?? 'No biography recorded.' }}</p>

                @if ($selected->notes)
                    <div class="mt-5 border-t border-line pt-5">
                        <p class="mb-1.5 text-[10px] uppercase tracking-[0.2em] text-faint">Notes</p>
                        <p class="text-sm leading-7 text-muted">{{ $selected->notes }}</p>
                    </div>
                @endif

                <div class="mt-7 grid grid-cols-2 gap-5 border-t border-line pt-6 text-xs">
                    <x-detail-item label="Records" class="font-semibold">{{ $selected->records_count }}</x-detail-item>
                </div>

                <div class="mt-8 flex gap-3">
                    <x-btn-secondary wire:click="closeModal" class="flex-1">Close</x-btn-secondary>
                    <x-btn-primary wire:click="switchToEdit" class="flex-1">Edit</x-btn-primary>
                </div>

            @elseif ($modalMode === 'delete' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-danger/60"></span>
                    <span class="text-[10px] uppercase tracking-[0.28em] text-danger">Strike from the Record</span>
                </div>

                <h2 class="font-serif text-2xl text-parchment-bright">Remove {{ $selected->name }}?</h2>
                <p class="mt-3 text-sm leading-6 text-muted">
                    Records credited to this author will remain, but lose their author link. This cannot be undone.
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
                    {{ $modalMode === 'edit' ? 'Amend Record' : 'Record a New Author' }}
                </h2>

                <form wire:submit="save" class="space-y-4">
                    <x-text-input model="name" label="Name" />
                    <x-textarea-input model="bio" label="Bio" :rows="3" />
                    <x-textarea-input model="notes" label="Notes" :rows="3" />

                    <div class="flex gap-3 pt-2">
                        <x-btn-secondary type="button" wire:click="closeModal" class="flex-1">Cancel</x-btn-secondary>
                        <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save" class="flex-1">
                            <span wire:loading.remove
                                wire:target="save">{{ $modalMode === 'edit' ? 'Save Changes' : 'Record Author' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </x-btn-primary>
                    </div>
                </form>
            @endif
        @endif
    </x-modal-shell>
</div>
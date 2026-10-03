<div>

    <x-flash-message />

    <x-page-header eyebrow="The Archive" title="Records">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Add Record</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    <x-summary-cards>
        <x-summary-card label="Records Shown">
            <p class="font-serif text-3xl text-[#d8c8a8]">{{ $summary['total'] }}</p>
        </x-summary-card>
        <x-summary-card label="Confidential">
            <p class="font-serif text-3xl text-[#d8c8a8]">{{ $summary['confidentialCount'] }}</p>
        </x-summary-card>
        <x-summary-card label="Most Significant" :last="true">
            <p class="font-serif text-lg text-[#b98967]">
                {{ $summary['mostSignificant']?->title ?? '—' }}
                @if ($summary['mostSignificant'])
                    <span class="text-sm text-[#756d5e]">({{ $summary['mostSignificant']->importance }})</span>
                @endif
            </p>
        </x-summary-card>
    </x-summary-cards>

    <x-filter-panel :active="$search || $categoryFilter || $eraFilter || $importanceFilter || $confidentialFilter !== '' || $authorFilter">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <x-text-input model="search" label="Search" placeholder="Title, excerpt, content..." variant="filter"
                :span="true" />

            <x-select-input model="categoryFilter" label="Category" :options="$categories" variant="filter"
                placeholder="Any" />

            <x-select-input model="eraFilter" label="Era" :options="$eras" variant="filter" placeholder="Any" />

            <x-select-input model="importanceFilter" label="Importance" :options="$importances" variant="filter"
                placeholder="Any" />

            {{-- Kept as plain markup: value/label don't match (0=Public, 1=Confidential), unlike every other select
            filter --}}
            <div>
                <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-[#806337]">Access</label>
                <select wire:model.live="confidentialFilter"
                    class="w-full border border-[#3b3225] bg-[#0d0c0a] px-3 py-2.5 text-xs text-[#e8dfca] outline-none focus:border-[#806337]">
                    <option value="">Any</option>
                    <option value="0">Public</option>
                    <option value="1">Confidential</option>
                </select>
            </div>

            <x-relation-picker label="Author" search-model="authorFilterSearch" placeholder="Search authors..."
                :options="$this->authorFilterResults" :selected-id="$authorFilter" :selected-name="$authorFilterName"
                select-action="selectAuthorFilter" clear-action="clearAuthorFilter" />
        </div>
    </x-filter-panel>

    {{-- Table --}}
    <div class="border border-[#2c2922] bg-[#151310]">
        {{-- Desktop / Tablet --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[900px] text-left">
                <thead>
                    <tr class="border-b border-[#2c2922] text-[8px] uppercase tracking-[0.2em] text-[#625744]">
                        @foreach (['title' => 'Title', 'category' => 'Category', 'era' => 'Era', 'importance' => 'Importance'] as $col => $label)
                            <x-sortable-th :column="$col" :label="$label" :sort-by="$sortBy"
                                :sort-direction="$sortDirection" />
                        @endforeach

                        <th class="whitespace-nowrap px-5 py-4">Author</th>
                        <th class="whitespace-nowrap px-5 py-4">Access</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#2c2922]/60">
                    @forelse ($records as $record)
                                    <tr class="transition hover:bg-[#191611]">
                                        <td class="px-5 py-4">
                                            <button wire:click="openView('{{ $record->slug }}')" class="text-left">
                                                <div class="font-serif text-sm text-[#ddd2bb]">{{ $record->title }}</div>
                                                <div class="text-[9px] uppercase tracking-[0.15em] text-[#625744]">
                                                    {{ $record->date ?: 'Undated' }}</div>
                                            </button>
                                        </td>

                                        <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $record->category }}</td>
                                        <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $record->era }}</td>

                                        <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em]">
                                            <x-severity-text :value="$record->importance" :colors="[
                            'Critical' => 'text-[#c14545]',
                            'Important' => 'text-[#b98967]',
                        ]" />
                                        </td>

                                        <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $record->author->name ?? 'Unknown' }}</td>

                                        <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em]">
                                            <x-severity-text :value="$record->confidential ? 'Confidential' : 'Public'"
                                                :colors="['Confidential' => 'text-[#c14545]']" default="text-[#625744]" />
                                        </td>

                                        <td class="px-5 py-4">
                                            <x-row-actions :id="$record->slug" />
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <x-empty-state title="No records archived." hint="Begin by adding the first entry."
                                    :filtered="$search || $categoryFilter || $eraFilter || $importanceFilter || $confidentialFilter !== '' || $authorFilter"
                                    filtered-title="No records match these filters."
                                    filtered-hint="to see the full archive." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="divide-y divide-[#2c2922]/60 md:hidden">
            @forelse ($records as $record)
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <button wire:click="openView('{{ $record->slug }}')" class="min-w-0 text-left">
                                <div class="font-serif text-base text-[#ddd2bb]">{{ $record->title }}</div>
                                <div class="mt-1 text-[9px] uppercase tracking-[0.15em] text-[#625744]">
                                    {{ $record->date ?: 'Undated' }}</div>
                            </button>
                            <div class="shrink-0 text-right">
                                <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Importance</div>
                                <div class="mt-1 text-sm font-semibold">
                                    <x-severity-text :value="$record->importance" :colors="[
                    'Critical' => 'text-[#c14545]',
                    'Important' => 'text-[#b98967]',
                ]" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Category</div>
                                <div class="mt-1 text-xs text-[#8f826b]">{{ $record->category }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Era</div>
                                <div class="mt-1 text-xs text-[#8f826b]">{{ $record->era }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Author</div>
                                <div class="mt-1 text-xs text-[#8f826b]">{{ $record->author->name ?? 'Unknown' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Access</div>
                                <div class="mt-1 text-[9px] uppercase tracking-[0.15em]">
                                    <x-severity-text :value="$record->confidential ? 'Confidential' : 'Public'"
                                        :colors="['Confidential' => 'text-[#c14545]']" default="text-[#625744]" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-end gap-5 border-t border-[#2c2922]/60 pt-4">
                            <x-row-actions :id="$record->slug" />
                        </div>
                    </div>
            @empty
                <div class="px-5 py-16 text-center">
                    <x-empty-state title="No records archived." hint="Begin by adding the first entry." :filtered="$search || $categoryFilter || $eraFilter || $importanceFilter || $confidentialFilter !== '' || $authorFilter"
                        filtered-title="No records match these filters." filtered-hint="to see the full archive." />
                </div>
            @endforelse
        </div>
    </div>

    <x-cursor-pagination :paginator="$records" :total="$summary['total']" />

    <x-modal-shell>
        @if ($showModal)
            @if ($modalMode === 'view' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-[#806337]"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-[#a17e43]">{{ $selected->category }}</span>
                    @if ($selected->confidential)
                        <span class="ml-auto text-[9px] uppercase tracking-[0.2em] text-[#c14545]">Confidential</span>
                    @endif
                </div>

                <h2 class="font-serif text-3xl text-[#e8dfca]">{{ $selected->title }}</h2>
                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-[#806337]">{{ $selected->era }} ·
                    {{ $selected->date ?: 'Undated' }}</p>

                <p class="mt-5 whitespace-pre-line text-sm leading-7 text-[#8f826b]">
                    {{ $selected->content ?: ($selected->excerpt ?? 'No content on file.') }}</p>

                <div class="mt-7 grid grid-cols-2 gap-5 border-t border-[#2c2922] pt-6 text-xs">
                    <x-detail-item label="Author">{{ $selected->author->name ?? 'Unknown' }}</x-detail-item>
                    <div>
                        <p class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">Importance</p>
                        <p class="mt-1 font-semibold">
                            <x-severity-text :value="$selected->importance" default="text-[#cdbd9e]" :colors="[
                        'Critical' => 'text-[#c14545]',
                        'Important' => 'text-[#b98967]',
                    ]" />
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <x-btn-secondary wire:click="closeModal" class="flex-1">Close</x-btn-secondary>
                    <x-btn-primary wire:click="switchToEdit" class="flex-1">Edit</x-btn-primary>
                </div>

            @elseif ($modalMode === 'delete' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-[#c14545]/60"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-[#c14545]">Strike from the Archive</span>
                </div>

                <h2 class="font-serif text-2xl text-[#e8dfca]">Remove {{ $selected->title }}?</h2>
                <p class="mt-3 text-sm leading-6 text-[#8f826b]">
                    This permanently erases <span class="text-[#cdbd9e]">{{ $selected->title }}</span> from the archive. This
                    cannot be undone.
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
                    {{ $modalMode === 'edit' ? 'Amend Record' : 'Add a New Record' }}
                </h2>

                <form wire:submit="save" class="space-y-4">
                    <x-text-input model="title" label="Title" />
                    <x-textarea-input model="excerpt" label="Excerpt" :rows="2" />
                    <x-textarea-input model="content" label="Content" :rows="6" />

                    <div class="grid grid-cols-2 gap-4">
                        <x-select-input model="category" label="Category" :options="$categories" />
                        <x-select-input model="era" label="Era" :options="$eras" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-text-input model="date" label="Date" placeholder="e.g. Year 411" />

                        <div>
                            <x-relation-picker label="Author" search-model="authorFormSearch" placeholder="Search authors..."
                                :options="$this->authorFormResults" :selected-id="$authorId" :selected-name="$authorName"
                                select-action="selectAuthorForm" clear-action="clearAuthorForm" clear-label="Unknown"
                                option-label-key="label" />
                            @error('authorId')
                            <p class="mt-1.5 text-[10px] text-[#c14545]">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-select-input model="importance" label="Importance" :options="$importances" />

                        <div class="flex items-end pb-2.5">
                            <label class="flex items-center gap-2 text-xs text-[#c8b895]">
                                <input type="checkbox" wire:model="confidential" class="accent-[#806337]">
                                Restricted / confidential
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <x-btn-secondary type="button" wire:click="closeModal" class="flex-1">Cancel</x-btn-secondary>
                        <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save" class="flex-1">
                            <span wire:loading.remove
                                wire:target="save">{{ $modalMode === 'edit' ? 'Save Changes' : 'Add Record' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </x-btn-primary>
                    </div>
                </form>
            @endif
        @endif
    </x-modal-shell>
</div>
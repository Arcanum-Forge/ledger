{{-- users/index.blade.php --}}
<div>

    @island(name: 'flash', always: true)
        <div>
            <x-flash-message />
        </div>
    @endisland

    <x-page-header eyebrow="The Keep" title="Users">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Add User</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    @island(name: 'summary', lazy: true, always: true)
        @placeholder
            <x-summary-cards>
                <x-summary-card label="Users Shown">
                    <div class="h-9 w-14 animate-pulse bg-line/60"></div>
                </x-summary-card>
                <x-summary-card label="Verified">
                    <div class="h-9 w-14 animate-pulse bg-line/60"></div>
                </x-summary-card>
                <x-summary-card label="Newest" :last="true">
                    <div class="h-7 w-32 animate-pulse bg-line/60"></div>
                </x-summary-card>
            </x-summary-cards>
        @endplaceholder

        @php
            $summary = $this->summary
        @endphp

        <x-summary-cards>
            <x-summary-card label="Users Shown">
                <p class="font-serif text-3xl text-parchment">{{ $summary['total'] }}</p>
            </x-summary-card>
            <x-summary-card label="Verified">
                <p class="font-serif text-3xl text-parchment">{{ $summary['verifiedCount'] }}</p>
            </x-summary-card>
            <x-summary-card label="Newest" :last="true">
                <p class="font-serif text-lg text-gold">{{ $summary['newest']?->name ?? '—' }}</p>
            </x-summary-card>
        </x-summary-cards>
    @endisland

    <x-filter-panel :active="$this->filtersActive">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-text-input model="search" label="Search" placeholder="Name or email..." variant="filter" />
            <div>
                <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-line">Verification</label>
                <select wire:model.live="verifiedFilter"
                    class="w-full border border-line bg-card-inset px-3 py-2.5 text-xs text-parchment-bright outline-none focus:border-line">
                    <option value="">Any</option>
                    <option value="1">Verified</option>
                    <option value="0">Unverified</option>
                </select>
            </div>
        </div>

        @if ($this->filtersActive)
            <button wire:click="clearFilters"
                class="mt-4 text-[9px] uppercase tracking-[0.15em] text-muted hover:text-gold">
                Clear filters
            </button>
        @endif
    </x-filter-panel>

    @island(name: 'table', lazy: true, always: true)
        @placeholder
            <div class="border border-line bg-card">
                <div class="min-h-[28rem] animate-pulse divide-y divide-line/60">
                    <div class="flex gap-6 px-5 py-4">
                        <div class="h-2.5 w-24 bg-line/60"></div>
                        <div class="h-2.5 w-24 bg-line/60"></div>
                        <div class="h-2.5 w-16 bg-line/60"></div>
                        <div class="h-2.5 w-16 bg-line/60"></div>
                    </div>
                    @foreach (range(1, 6) as $i)
                        <div class="flex items-center gap-6 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-line/60"></div>
                                <div class="h-3.5 w-32 bg-line/60"></div>
                            </div>
                            <div class="h-3 w-44 bg-line/40"></div>
                            <div class="h-3 w-24 bg-line/40"></div>
                            <div class="h-3 flex-1 bg-line/40"></div>
                            <div class="h-3 w-20 bg-line/60"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endplaceholder

        @php
            $users = $this->users
        @endphp

        <div class="border border-line bg-card">
            {{-- Desktop / Tablet --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[700px] text-left">
                    <thead>
                        <tr class="border-b border-line text-[8px] uppercase tracking-[0.2em] text-faint">
                            @foreach (['name' => 'Name', 'email' => 'Email', 'created_at' => 'Joined'] as $col => $label)
                                <x-sortable-th :column="$col" :label="$label" :sort-by="$this->sortBy"
                                    :sort-direction="$this->sortDirection" />
                            @endforeach

                            <th class="whitespace-nowrap px-5 py-4">Status</th>
                            <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-line/60">
                        @forelse ($users as $user)
                            <tr class="transition hover:bg-card-hover">
                                <td class="px-5 py-4">
                                    {{-- wire:island="modal": the modal lives in its own island, so scope the update there --}}
                                    <button wire:click="openView({{ $user->id }})" wire:island="modal"
                                        class="flex items-center gap-3 text-left">
                                        <x-avatar :user="$user" size="sm" />
                                        <span class="font-serif text-sm text-parchment">{{ $user->name }}</span>
                                    </button>
                                </td>

                                <td class="px-5 py-4 text-xs text-muted">{{ $user->email }}</td>
                                <td class="px-5 py-4 text-xs text-muted">
                                    {{ $user->created_at?->format('M j, Y') ?? '—' }}
                                </td>

                                <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em]">
                                    <x-severity-text :value="$user->email_verified_at ? 'Verified' : 'Unverified'"
                                        :colors="['Verified' => 'text-[#8fae7a]']" default="text-gold" />
                                </td>

                                <td class="px-5 py-4">
                                    <x-row-actions :id="$user->id" island="modal" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <x-empty-state title="No users yet." hint="Begin by adding the first account."
                                        :filtered="$this->filtersActive" filtered-title="No users match these filters."
                                        filtered-hint="to see everyone." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="divide-y divide-line/60 md:hidden">
                @forelse ($users as $user)
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <button wire:click="openView({{ $user->id }})" wire:island="modal"
                                class="flex min-w-0 items-center gap-3 text-left">
                                <x-avatar :user="$user" size="md" />
                                <span class="min-w-0">
                                    <span
                                        class="block truncate font-serif text-base text-parchment">{{ $user->name }}</span>
                                    <span class="block truncate text-xs text-faint">{{ $user->email }}</span>
                                </span>
                            </button>
                            <div class="shrink-0 text-right">
                                <div class="text-[9px] uppercase tracking-[0.15em]">
                                    <x-severity-text :value="$user->email_verified_at ? 'Verified' : 'Unverified'"
                                        :colors="['Verified' => 'text-[#8fae7a]']" default="text-gold" />
                                </div>
                            </div>
                        </div>

                        <p class="mt-4 text-xs text-muted">Joined {{ $user->created_at?->format('M j, Y') ?? '—' }}
                        </p>

                        <div class="mt-5 flex items-center justify-end gap-5 border-t border-line/60 pt-4">
                            <x-row-actions :id="$user->id" island="modal" />
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-16 text-center">
                        <x-empty-state title="No users yet." hint="Begin by adding the first account."
                            :filtered="$this->filtersActive" filtered-title="No users match these filters."
                            filtered-hint="to see everyone." />
                    </div>
                @endforelse
            </div>
        </div>

        <x-cursor-pagination :paginator="$users" :total="$this->total" />
    @endisland

    @island(name: 'modal', always: true)
        <x-modal-shell>
            @if ($this->showModal)
                @if ($this->modalMode === 'view' && $this->selected)
                    <div class="mb-6 flex items-center gap-4">
                        <x-avatar :user="$this->selected" size="lg" />
                        <div>
                            <h2 class="font-serif text-2xl text-parchment-bright">{{ $this->selected->name }}</h2>
                            <p class="mt-0.5 text-xs text-muted">{{ $this->selected->email }}</p>
                        </div>
                    </div>

                    <div class="mt-7 grid grid-cols-2 gap-5 border-t border-line pt-6 text-xs">
                        <div>
                            <p class="text-[8px] uppercase tracking-[0.15em] text-faint">Status</p>
                            <p class="mt-1">
                                <x-severity-text
                                    :value="$this->selected->email_verified_at ? 'Verified' : 'Unverified'"
                                    :colors="['Verified' => 'text-[#8fae7a]']" default="text-gold" />
                            </p>
                        </div>
                        <x-detail-item
                            label="Joined">{{ $this->selected->created_at?->format('M j, Y') ?? '—' }}</x-detail-item>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <x-btn-secondary wire:click="closeModal" class="flex-1">Close</x-btn-secondary>
                        <x-btn-primary wire:click="switchToEdit" class="flex-1">Edit</x-btn-primary>
                    </div>

                @elseif ($this->modalMode === 'delete' && $this->selected)
                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-8 bg-danger/60"></span>
                        <span class="text-[9px] uppercase tracking-[0.28em] text-danger">Revoke Access</span>
                    </div>

                    <h2 class="font-serif text-2xl text-parchment-bright">Remove {{ $this->selected->name }}?</h2>
                    <p class="mt-3 text-sm leading-6 text-muted">
                        This permanently deletes <span
                            class="text-parchment-dim">{{ $this->selected->email }}</span> and revokes all access.
                        This cannot be undone.
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
                        {{ $this->modalMode === 'edit' ? 'Amend User' : 'Add a New User' }}
                    </h2>

                    <form wire:submit="save" class="space-y-4">
                        <x-text-input model="name" label="Name" />
                        <x-text-input model="email" label="Email" type="email" />

                        <div class="grid grid-cols-2 gap-4">
                            <x-text-input model="password"
                                :label="'Password' . ($this->modalMode === 'edit' ? ' (leave blank to keep)' : '')"
                                type="password" />
                            <div>
                                <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-line">Confirm
                                    Password</label>
                                <input type="password" wire:model="password_confirmation"
                                    class="w-full border border-line bg-card-inset px-4 py-2.5 text-sm text-parchment-bright outline-none focus:border-line">
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-xs text-parchment-dim">
                                <input type="checkbox" wire:model="verified" class="accent-line">
                                Email verified
                            </label>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <x-btn-secondary type="button" wire:click="closeModal"
                                class="flex-1">Cancel</x-btn-secondary>
                            <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="flex-1">
                                <span wire:loading.remove
                                    wire:target="save">{{ $this->modalMode === 'edit' ? 'Save Changes' : 'Add User' }}</span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </x-btn-primary>
                        </div>
                    </form>
                @endif
            @endif
        </x-modal-shell>
    @endisland
</div>
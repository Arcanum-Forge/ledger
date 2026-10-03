<div>

    <x-flash-message />

    <x-page-header eyebrow="The Keep" title="Users">
        <x-slot:actions>
            <x-btn-primary wire:click="openCreate">Add User</x-btn-primary>
        </x-slot:actions>
    </x-page-header>

    <x-summary-cards>
        <x-summary-card label="Users Shown">
            <p class="font-serif text-3xl text-[#d8c8a8]">{{ $summary['total'] }}</p>
        </x-summary-card>
        <x-summary-card label="Verified">
            <p class="font-serif text-3xl text-[#d8c8a8]">{{ $summary['verifiedCount'] }}</p>
        </x-summary-card>
        <x-summary-card label="Newest" :last="true">
            <p class="font-serif text-lg text-[#b98967]">{{ $summary['newest']?->name ?? '—' }}</p>
        </x-summary-card>
    </x-summary-cards>

    <x-filter-panel :active="$search || $verifiedFilter !== ''">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-text-input model="search" label="Search" placeholder="Name or email..." variant="filter" />

            {{-- Kept as plain markup: value/label don't match (0=Unverified, 1=Verified), unlike every other select filter --}}
            <div>
                <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-[#806337]">Verification</label>
                <select wire:model.live="verifiedFilter"
                    class="w-full border border-[#3b3225] bg-[#0d0c0a] px-3 py-2.5 text-xs text-[#e8dfca] outline-none focus:border-[#806337]">
                    <option value="">Any</option>
                    <option value="1">Verified</option>
                    <option value="0">Unverified</option>
                </select>
            </div>
        </div>

        @if ($search || $verifiedFilter !== '')
            <button wire:click="clearFilters"
                class="mt-4 text-[9px] uppercase tracking-[0.15em] text-[#857861] hover:text-[#c59b4a]">
                Clear filters
            </button>
        @endif
    </x-filter-panel>

    {{-- Table --}}
    <div class="border border-[#2c2922] bg-[#151310]">
        {{-- Desktop / Tablet --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[700px] text-left">
                <thead>
                    <tr class="border-b border-[#2c2922] text-[8px] uppercase tracking-[0.2em] text-[#625744]">
                        @foreach (['name' => 'Name', 'email' => 'Email', 'created_at' => 'Joined'] as $col => $label)
                            <x-sortable-th :column="$col" :label="$label" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                        @endforeach

                        <th class="whitespace-nowrap px-5 py-4">Status</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#2c2922]/60">
                    @forelse ($users as $user)
                        <tr class="transition hover:bg-[#191611]">
                            <td class="px-5 py-4">
                                <button wire:click="openView({{ $user->id }})" class="flex items-center gap-3 text-left">
                                    <x-avatar :user="$user" size="sm" />
                                    <span class="font-serif text-sm text-[#ddd2bb]">{{ $user->name }}</span>
                                </button>
                            </td>

                            <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $user->email }}</td>
                            <td class="px-5 py-4 text-xs text-[#8f826b]">{{ $user->created_at?->format('M j, Y') ?? '—' }}</td>

                            <td class="px-5 py-4 text-[9px] uppercase tracking-[0.15em]">
                                <x-severity-text :value="$user->email_verified_at ? 'Verified' : 'Unverified'" :colors="['Verified' => 'text-[#8fae7a]']" default="text-[#c59b4a]" />
                            </td>

                            <td class="px-5 py-4">
                                <x-row-actions :id="$user->id" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <x-empty-state
                                    title="No users yet."
                                    hint="Begin by adding the first account."
                                    :filtered="$search || $verifiedFilter !== ''"
                                    filtered-title="No users match these filters."
                                    filtered-hint="to see everyone."
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="divide-y divide-[#2c2922]/60 md:hidden">
            @forelse ($users as $user)
                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <button wire:click="openView({{ $user->id }})" class="flex min-w-0 items-center gap-3 text-left">
                            <x-avatar :user="$user" size="md" />
                            <span class="min-w-0">
                                <span class="block truncate font-serif text-base text-[#ddd2bb]">{{ $user->name }}</span>
                                <span class="block truncate text-xs text-[#625744]">{{ $user->email }}</span>
                            </span>
                        </button>
                        <div class="shrink-0 text-right">
                            <div class="text-[9px] uppercase tracking-[0.15em]">
                                <x-severity-text :value="$user->email_verified_at ? 'Verified' : 'Unverified'" :colors="['Verified' => 'text-[#8fae7a]']" default="text-[#c59b4a]" />
                            </div>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-[#8f826b]">Joined {{ $user->created_at?->format('M j, Y') ?? '—' }}</p>

                    <div class="mt-5 flex items-center justify-end gap-5 border-t border-[#2c2922]/60 pt-4">
                        <x-row-actions :id="$user->id" />
                    </div>
                </div>
            @empty
                <div class="px-5 py-16 text-center">
                    <x-empty-state
                        title="No users yet."
                        hint="Begin by adding the first account."
                        :filtered="$search || $verifiedFilter !== ''"
                        filtered-title="No users match these filters."
                        filtered-hint="to see everyone."
                    />
                </div>
            @endforelse
        </div>
    </div>

    <x-cursor-pagination :paginator="$users" :total="$summary['total']" />

    <x-modal-shell>
        @if ($showModal)
            @if ($modalMode === 'view' && $selected)
                <div class="mb-6 flex items-center gap-4">
                    <x-avatar :user="$selected" size="lg" />
                    <div>
                        <h2 class="font-serif text-2xl text-[#e8dfca]">{{ $selected->name }}</h2>
                        <p class="mt-0.5 text-xs text-[#8f826b]">{{ $selected->email }}</p>
                    </div>
                </div>

                <div class="mt-7 grid grid-cols-2 gap-5 border-t border-[#2c2922] pt-6 text-xs">
                    <div>
                        <p class="text-[#625744] uppercase tracking-[0.15em] text-[8px]">Status</p>
                        <p class="mt-1">
                            <x-severity-text :value="$selected->email_verified_at ? 'Verified' : 'Unverified'" :colors="['Verified' => 'text-[#8fae7a]']" default="text-[#c59b4a]" />
                        </p>
                    </div>
                    <x-detail-item label="Joined">{{ $selected->created_at?->format('M j, Y') ?? '—' }}</x-detail-item>
                </div>

                <div class="mt-8 flex gap-3">
                    <x-btn-secondary wire:click="closeModal" class="flex-1">Close</x-btn-secondary>
                    <x-btn-primary wire:click="switchToEdit" class="flex-1">Edit</x-btn-primary>
                </div>

            @elseif ($modalMode === 'delete' && $selected)
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-8 bg-[#c14545]/60"></span>
                    <span class="text-[9px] uppercase tracking-[0.28em] text-[#c14545]">Revoke Access</span>
                </div>

                <h2 class="font-serif text-2xl text-[#e8dfca]">Remove {{ $selected->name }}?</h2>
                <p class="mt-3 text-sm leading-6 text-[#8f826b]">
                    This permanently deletes <span class="text-[#cdbd9e]">{{ $selected->email }}</span> and revokes all access. This cannot be undone.
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
                    {{ $modalMode === 'edit' ? 'Amend User' : 'Add a New User' }}
                </h2>

                <form wire:submit="save" class="space-y-4">
                    <x-text-input model="name" label="Name" />
                    <x-text-input model="email" label="Email" type="email" />

                    <div class="grid grid-cols-2 gap-4">
                        <x-text-input model="password" :label="'Password' . ($modalMode === 'edit' ? ' (leave blank to keep)' : '')" type="password" />

                        {{-- No wire:model error/validation needed on confirmation itself — Laravel's 'confirmed' rule validates against password, not this field --}}
                        <div>
                            <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-[#806337]">Confirm Password</label>
                            <input type="password" wire:model="password_confirmation"
                                class="w-full border border-[#3b3225] bg-[#0d0c0a] px-4 py-2.5 text-sm text-[#e8dfca] outline-none focus:border-[#806337]">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-xs text-[#c8b895]">
                            <input type="checkbox" wire:model="verified" class="accent-[#806337]">
                            Email verified
                        </label>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <x-btn-secondary type="button" wire:click="closeModal" class="flex-1">Cancel</x-btn-secondary>
                        <x-btn-primary type="submit" wire:loading.attr="disabled" wire:target="save" class="flex-1">
                            <span wire:loading.remove wire:target="save">{{ $modalMode === 'edit' ? 'Save Changes' : 'Add User' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </x-btn-primary>
                    </div>
                </form>
            @endif
        @endif
    </x-modal-shell>
</div>
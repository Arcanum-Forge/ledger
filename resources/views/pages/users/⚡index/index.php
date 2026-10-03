<?php

use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Title('Ledger — Users')]
    class extends Component {

    use WithPagination;
    use HasCursorPagination;
    use HasModalCrud;

    public ?User $selected = null;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $verified = false;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $verifiedFilter = ''; // '' = any, '1' = verified, '0' = unverified

    #[Url(history: true)]
    public string $sortBy = 'name';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    public function clearFilters(): void
    {
        $this->reset(['search', 'verifiedFilter']);
        $this->cursor = null;
    }

    /**
     * Shared filtered query — used both for the paginated list and the
     * summary aggregates, so the summary always reflects what's filtered,
     * not the whole table.
     */
    protected function filteredQuery(): Builder
    {
        return User::query()
            ->when($this->search, fn(Builder $q) => $q->where(function (Builder $q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->verifiedFilter !== '', fn(Builder $q) => $this->verifiedFilter === '1'
                ? $q->whereNotNull('email_verified_at')
                : $q->whereNull('email_verified_at'));
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->selected?->id)],
            'password' => [$this->selected ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function openView(User $user): void
    {
        $this->fillForm($user);
        $this->modalMode = 'view';
        $this->showModal = true;
    }

    public function openEdit(User $user): void
    {
        $this->fillForm($user);
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function openDelete(User $user): void
    {
        $this->selected = $user;
        $this->modalMode = 'delete';
        $this->showModal = true;
    }

    public function confirmDelete(): void
    {
        $this->selected?->delete();
        session()->flash('success', 'User record removed from the archive.');
        $this->closeModal();
    }

    public function save(): void
    {
        $data = $this->validate();

        if (empty($data['password'])) {
            // Blank on edit means "leave it unchanged" — don't touch the
            // existing hash. The cast on User handles hashing whenever a
            // password value is actually present.
            unset($data['password']);
        }

        $data['email_verified_at'] = $this->verified ? now() : null;

        if ($this->selected) {
            $this->selected->update($data);
            session()->flash('success', 'User record updated.');
        } else {
            User::create($data);
            session()->flash('success', 'User record added to the archive.');
        }

        $this->closeModal();
    }

    protected function fillForm(User $user): void
    {
        $this->selected = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->verified = !is_null($user->email_verified_at);
    }

    protected function resetForm(): void
    {
        $this->selected = null;
        $this->reset(['name', 'email', 'password', 'password_confirmation']);
        $this->verified = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $filtered = $this->filteredQuery();

        $summary = [
            'total' => (clone $filtered)->count(),
            'verifiedCount' => (clone $filtered)->whereNotNull('email_verified_at')->count(),
            'newest' => (clone $filtered)->orderByDesc('created_at')->orderByDesc('id')->first(),
        ];

        $users = $filtered
            ->orderBy($this->sortBy, $this->sortDirection)
            ->orderBy('id', $this->sortDirection) // tiebreaker: keeps cursor pagination stable when sort column has duplicates
            ->cursorPaginate(
                perPage: 10,
                cursorName: 'cursor',
                cursor: $this->cursor ? Cursor::fromEncoded($this->cursor) : null,
            );

        return $this->view([
            'users' => $users,
            'summary' => $summary,
        ]);
    }

};
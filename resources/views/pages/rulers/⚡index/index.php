<?php

use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Models\Ruler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Title('Ledger — Rulers')]
    class extends Component {

    use WithPagination;
    use HasCursorPagination;
    use HasModalCrud;

    public ?Ruler $selected = null;

    public string $honorific = 'King';
    public string $name = '';
    public string $bio = '';
    public string $notes = '';

    public array $honorifics = [
        'King',
        'Queen',
        'High King',
        'High Queen',
        'Lord',
        'Lady',
        'Prince',
        'Princess',
        'Emperor',
        'Empress',
        'Sultan',
        'Sultana',
        'Duke',
        'Duchess',
        'Archduke',
        'Archduchess',
        'Grand Duke',
        'Grand Duchess',
        'Jarl',
        'Jarl-King',
        'Great Khan',
        'Khan',
        'Warlord',
        'Lord Protector',
        'Lady Protector',
        'Regent',
        'High Regent',
        'Divine Regent',
        'Archmage',
        'High Chancellor',
        'First Consul',
        'Consul',
        'Prince-Regent',
        'Princess-Regent',
        'Sea King',
        'Sea Queen',
        'Marsh Lord',
        'Marsh Lady',
        'Veiled King',
        'Veiled Queen',
        'Moon King',
        'Moon Queen',
        'Elder King',
        'Elder Queen',
    ];
    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $honorificFilter = '';

    #[Url(history: true)]
    public int $minKingdoms = 0;

    #[Url(history: true)]
    public int $maxKingdoms = 100;

    #[Url(history: true)]
    public string $sortBy = 'name';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    public function clearFilters(): void
    {
        $this->reset(['search', 'honorificFilter', 'minKingdoms', 'maxKingdoms']);
        $this->cursor = null;
    }

    /**
     * Shared filtered query — used both for the paginated list and the
     * summary aggregates, so the summary always reflects what's filtered,
     * not the whole table.
     *
     * kingdoms_count from withCount() is a subquery alias, not a real
     * column. Filtering it with having() works on MySQL but breaks on
     * SQLite ("HAVING clause on a non-aggregate query"). has() sidesteps
     * that: it adds its own correlated count subquery in a WHERE clause,
     * which every driver accepts.
     */
    protected function filteredQuery(): Builder
    {
        return Ruler::query()
            ->withCount('kingdoms')
            ->when($this->search, fn(Builder $q) => $q->where(function (Builder $q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('bio', 'like', "%{$this->search}%")
                    ->orWhere('notes', 'like', "%{$this->search}%");
            }))
            ->when($this->honorificFilter, fn(Builder $q) => $q->where('honorific', $this->honorificFilter))
            ->has('kingdoms', '>=', $this->minKingdoms)
            ->has('kingdoms', '<=', $this->maxKingdoms);
    }

    protected function rules(): array
    {
        return [
            'honorific' => ['required', 'string', 'max:100', 'in:' . implode(',', $this->honorifics)],
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function openView(Ruler $ruler): void
    {
        $this->fillForm($ruler);
        $this->modalMode = 'view';
        $this->showModal = true;
    }

    public function openEdit(Ruler $ruler): void
    {
        $this->fillForm($ruler);
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function openDelete(Ruler $ruler): void
    {
        $this->selected = $ruler;
        $this->modalMode = 'delete';
        $this->showModal = true;
    }

    public function confirmDelete(): void
    {
        $this->selected?->delete();
        session()->flash('success', 'Ruler record removed from the archive.');
        $this->closeModal();
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->selected) {
            $this->selected->update($data);
            session()->flash('success', 'Ruler record updated.');
        } else {
            $data['slug'] = Ruler::uniqueSlug($this->name);
            Ruler::create($data);
            session()->flash('success', 'Ruler record added to the archive.');
        }

        $this->closeModal();
    }

    protected function fillForm(Ruler $ruler): void
    {
        $this->selected = $ruler->loadCount('kingdoms');
        $this->honorific = $ruler->honorific;
        $this->name = $ruler->name;
        $this->bio = $ruler->bio ?? '';
        $this->notes = $ruler->notes ?? '';
    }

    protected function resetForm(): void
    {
        $this->selected = null;
        $this->reset(['name', 'bio', 'notes']);
        $this->honorific = 'King';
        $this->resetErrorBag();
    }

    public function render()
    {
        $filtered = $this->filteredQuery();

        // avgKingdoms is computed in PHP over the fetched collection rather
        // than via a SQL avg(), because 'kingdoms_count' is a withCount()
        // alias — an aggregate() query against it only round-trips
        // correctly through Laravel's having-wrapping path, which we're
        // deliberately not using (see filteredQuery()).
        $summary = [
            'total' => (clone $filtered)->count(),
            'avgKingdoms' => (int) round((clone $filtered)->get()->avg('kingdoms_count') ?? 0),
            'mostPopulous' => (clone $filtered)->orderByDesc('kingdoms_count')->first(),
        ];

        $rulers = $filtered
            ->orderBy($this->sortBy, $this->sortDirection)
            ->orderBy('id', $this->sortDirection) // tiebreaker: keeps cursor pagination stable when sort column has duplicates
            ->cursorPaginate(
                perPage: 10,
                cursorName: 'cursor',
                cursor: $this->cursor ? Cursor::fromEncoded($this->cursor) : null,
            );

        return $this->view([
            'rulers' => $rulers,
            'summary' => $summary,
        ]);
    }

};
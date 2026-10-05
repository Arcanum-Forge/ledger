<?php
// {{-- leaders/index.php --}}
use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Models\Leader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Title('Ledger — Leaders')]
    class extends Component
    {
        use HasCursorPagination;
        use HasModalCrud;
        use WithPagination;

        public ?Leader $selected = null;

        public string $name = '';

        public string $bio = '';

        public string $notes = '';

        #[Url(history: true)]
        public string $search = '';

        #[Url(history: true)]
        public int $minFactions = 0;

        #[Url(history: true)]
        public int $maxFactions = 100;

        #[Url(history: true)]
        public string $sortBy = 'name';

        #[Url(history: true)]
        public string $sortDirection = 'asc';

        public function clearFilters(): void
        {
            $this->reset(['search', 'minFactions', 'maxFactions']);
            $this->cursor = null;
        }

        /**
         * Islands can't see variables passed from render(), so everything
         * they need is exposed as a computed property and read via $this->.
         * Computed values are memoized per request.
         */
        #[Computed]
        public function filtersActive(): bool
        {
            return $this->search !== '' || $this->minFactions > 0 || $this->maxFactions < 100;
        }

        #[Computed]
        public function total(): int
        {
            return (clone $this->filteredQuery())->count();
        }

        /**
         * The heavy part (loads the whole filtered set to average it) —
         * this is what the lazy summary island defers past first paint.
         */
        #[Computed]
        public function summary(): array
        {
            $filtered = $this->filteredQuery();

            // avgFactions is computed in PHP over the fetched collection rather
            // than via a SQL avg(), because 'factions_count' is a withCount()
            // alias — see filteredQuery() for why we avoid having().
            return [
                'total' => $this->total,
                'avgFactions' => (int) round((clone $filtered)->get()->avg('factions_count') ?? 0),
                'mostFactions' => (clone $filtered)->orderByDesc('factions_count')->first(),
            ];
        }

        #[Computed]
        public function leaders(): CursorPaginator
        {
            return $this->filteredQuery()
                ->orderBy($this->sortBy, $this->sortDirection)
                ->orderBy('id', $this->sortDirection) // tiebreaker: keeps cursor pagination stable when sort column has duplicates
                ->cursorPaginate(
                    perPage: 10,
                    cursorName: 'cursor',
                    cursor: $this->cursor ? Cursor::fromEncoded($this->cursor) : null,
                );
        }

        /**
         * Shared filtered query — used both for the paginated list and the
         * summary aggregates, so the summary always reflects what's filtered,
         * not the whole table.
         *
         * factions_count from withCount() is a subquery alias, not a real
         * column. Filtering it with having() works on MySQL but breaks on
         * SQLite ("HAVING clause on a non-aggregate query"). has() sidesteps
         * that: it adds its own correlated count subquery in a WHERE clause,
         * which every driver accepts.
         */
        protected function filteredQuery(): Builder
        {
            return Leader::query()
                ->withCount('factions')
                ->when($this->search, fn (Builder $q) => $q->where(function (Builder $q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('bio', 'like', "%{$this->search}%")
                        ->orWhere('notes', 'like', "%{$this->search}%");
                }))
                ->has('factions', '>=', $this->minFactions)
                ->has('factions', '<=', $this->maxFactions);
        }

        protected function rules(): array
        {
            return [
                'name' => ['required', 'string', 'max:255'],
                'bio' => ['nullable', 'string'],
                'notes' => ['nullable', 'string'],
            ];
        }

        public function openView(Leader $leader): void
        {
            $this->fillForm($leader);
            $this->modalMode = 'view';
            $this->showModal = true;
        }

        public function openEdit(Leader $leader): void
        {
            $this->fillForm($leader);
            $this->modalMode = 'edit';
            $this->showModal = true;
        }

        public function openDelete(Leader $leader): void
        {
            $this->selected = $leader;
            $this->modalMode = 'delete';
            $this->showModal = true;
        }

        public function confirmDelete(): void
        {
            $this->selected?->delete();

            session()->flash('success', 'Leader record removed from the archive.');

            $this->closeModal();
            $this->refreshIslands();
        }

        public function save(): void
        {
            $data = $this->validate();

            if ($this->selected) {
                $this->selected->update($data);
                session()->flash('success', 'Leader record updated.');
            } else {
                $data['slug'] = Leader::uniqueSlug($this->name);
                Leader::create($data);
                session()->flash('success', 'Leader record added to the archive.');
            }

            $this->closeModal();
            $this->refreshIslands();
        }

        /**
         * save()/confirmDelete() run from inside the modal island, so by
         * default only the modal re-renders. Push the other islands too,
         * busting the memoized computeds first so they re-query.
         */
        protected function refreshIslands(): void
        {
            unset($this->leaders, $this->summary, $this->total);

            $this->renderIsland('flash');
            $this->renderIsland('summary');
            $this->renderIsland('table');
        }

        protected function fillForm(Leader $leader): void
        {
            $this->selected = $leader->loadCount('factions');
            $this->name = $leader->name;
            $this->bio = $leader->bio ?? '';
            $this->notes = $leader->notes ?? '';
        }

        protected function resetForm(): void
        {
            $this->selected = null;
            $this->reset(['name', 'bio', 'notes']);
            $this->resetErrorBag();
        }

    };
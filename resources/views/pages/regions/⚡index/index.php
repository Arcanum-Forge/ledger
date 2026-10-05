<?php
// {{-- regions/index.php --}}
use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Title('Ledger — Regions')]
    class extends Component
    {
        use HasCursorPagination;
        use HasModalCrud;
        use WithPagination;

        public ?Region $selected = null;

        public string $name = '';

        public string $description = '';

        #[Url(history: true)]
        public string $search = '';

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
            $this->reset(['search', 'minKingdoms', 'maxKingdoms']);
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
            return $this->search !== '' || $this->minKingdoms > 0 || $this->maxKingdoms < 100;
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

            // avgKingdoms is computed in PHP over the fetched collection rather
            // than via a SQL avg(), because 'kingdoms_count' is a withCount()
            // alias — see filteredQuery() for why we avoid having().
            return [
                'total' => $this->total,
                'avgKingdoms' => (int) round((clone $filtered)->get()->avg('kingdoms_count') ?? 0),
                'mostPopulous' => (clone $filtered)->orderByDesc('kingdoms_count')->first(),
            ];
        }

        #[Computed]
        public function regions(): CursorPaginator
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
         * kingdoms_count from withCount() is a subquery alias, not a real
         * column. Filtering on it with having() works on MySQL but fails on
         * SQLite ("HAVING clause on a non-aggregate query"), since SQLite
         * requires an actual aggregate function inside HAVING. has() sidesteps
         * this entirely: it adds a WHERE with its own correlated count
         * subquery, which every driver accepts.
         */
        protected function filteredQuery(): Builder
        {
            return Region::query()
                ->withCount('kingdoms')
                ->when($this->search, fn (Builder $q) => $q->where(function (Builder $q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                }))
                ->has('kingdoms', '>=', $this->minKingdoms)
                ->has('kingdoms', '<=', $this->maxKingdoms);
        }

        protected function rules(): array
        {
            return [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
            ];
        }

        public function openView(Region $region): void
        {
            $this->fillForm($region);
            $this->modalMode = 'view';
            $this->showModal = true;
        }

        public function openEdit(Region $region): void
        {
            $this->fillForm($region);
            $this->modalMode = 'edit';
            $this->showModal = true;
        }

        public function openDelete(Region $region): void
        {
            $this->selected = $region;
            $this->modalMode = 'delete';
            $this->showModal = true;
        }

        public function confirmDelete(): void
        {
            $this->selected?->delete();
            session()->flash('success', 'Region record removed from the archive.');
            $this->closeModal();
            $this->refreshIslands();
        }

        public function save(): void
        {
            $data = $this->validate();

            if ($this->selected) {
                $this->selected->update($data);
                session()->flash('success', 'Region record updated.');
            } else {
                $data['slug'] = Region::uniqueSlug($this->name);
                Region::create($data);
                session()->flash('success', 'Region record added to the archive.');
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
            unset($this->regions, $this->summary, $this->total);

            $this->renderIsland('flash');
            $this->renderIsland('summary');
            $this->renderIsland('table');
        }

        protected function fillForm(Region $region): void
        {
            $this->selected = $region->loadCount('kingdoms');
            $this->name = $region->name;
            $this->description = $region->description ?? '';
        }

        protected function resetForm(): void
        {
            $this->selected = null;
            $this->reset(['name', 'description']);
            $this->resetErrorBag();
        }

    };
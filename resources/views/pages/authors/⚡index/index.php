<?php
// {{-- authors/index.php --}}

use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Title('Ledger — Authors')]
    class extends Component
    {
        use HasCursorPagination;
        use HasModalCrud;
        use WithPagination;

        public ?Author $selected = null;

        public string $name = '';

        public string $bio = '';

        public string $notes = '';

        #[Url(history: true)]
        public string $search = '';

        #[Url(history: true)]
        public int $minRecords = 0;

        #[Url(history: true)]
        public int $maxRecords = 100;

        #[Url(history: true)]
        public string $sortBy = 'name';

        #[Url(history: true)]
        public string $sortDirection = 'asc';

        public function clearFilters(): void
        {
            $this->reset(['search', 'minRecords', 'maxRecords']);
            $this->cursor = null;
        }

        /**
         * Islands can't see variables passed from render() (they only see
         * component state), so everything an island needs is exposed as a
         * computed property and read via $this-> in the template.
         * Computed values are memoized per request, so the query runs once
         * even if several islands read it.
         */
        #[Computed]
        public function filtersActive(): bool
        {
            return $this->search !== '' || $this->minRecords > 0 || $this->maxRecords < 100;
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

            // avgRecords is computed in PHP over the fetched collection rather
            // than via a SQL avg(), because 'records_count' is a withCount()
            // alias — see filteredQuery() for why we avoid having().
            return [
                'total' => $this->total,
                'avgRecords' => (int) round((clone $filtered)->get()->avg('records_count') ?? 0),
                'mostRecords' => (clone $filtered)->orderByDesc('records_count')->first(),
            ];
        }

        #[Computed]
        public function authors(): CursorPaginator
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
         * records_count from withCount() is a subquery alias, not a real
         * column. Filtering it with having() works on MySQL but breaks on
         * SQLite ("HAVING clause on a non-aggregate query"). has() sidesteps
         * that: it adds its own correlated count subquery in a WHERE clause,
         * which every driver accepts.
         */
        protected function filteredQuery(): Builder
        {
            return Author::query()
                ->withCount('records')
                ->when($this->search, fn (Builder $q) => $q->where(function (Builder $q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('bio', 'like', "%{$this->search}%")
                        ->orWhere('notes', 'like', "%{$this->search}%");
                }))
                ->has('records', '>=', $this->minRecords)
                ->has('records', '<=', $this->maxRecords);
        }

        protected function rules(): array
        {
            return [
                'name' => ['required', 'string', 'max:255'],
                'bio' => ['nullable', 'string'],
                'notes' => ['nullable', 'string'],
            ];
        }

        public function openView(Author $author): void
        {
            $this->fillForm($author);
            $this->modalMode = 'view';
            $this->showModal = true;
        }

        public function openEdit(Author $author): void
        {
            $this->fillForm($author);
            $this->modalMode = 'edit';
            $this->showModal = true;
        }

        public function openDelete(Author $author): void
        {
            $this->selected = $author;
            $this->modalMode = 'delete';
            $this->showModal = true;
        }

        public function confirmDelete(): void
        {
            $this->selected?->delete();

            session()->flash('success', 'Author record removed from the archive.');

            $this->closeModal();
            $this->refreshIslands();
        }

        public function save(): void
        {
            $data = $this->validate();

            if ($this->selected) {
                $this->selected->update($data);
                session()->flash('success', 'Author record updated.');
            } else {
                $data['slug'] = Author::uniqueSlug($this->name);
                Author::create($data);
                session()->flash('success', 'Author record added to the archive.');
            }

            $this->closeModal();
            $this->refreshIslands();
        }

        /**
         * save()/confirmDelete() run from inside the modal island, so by
         * default only the modal re-renders. Push the data islands too.
         * Bust the memoized computeds first so they re-query after the write.
         */
        protected function refreshIslands(): void
        {
            unset($this->authors, $this->summary, $this->total);

            $this->renderIsland('flash');
            $this->renderIsland('summary');
            $this->renderIsland('table');
        }

        protected function fillForm(Author $author): void
        {
            $this->selected = $author->loadCount('records');
            $this->name = $author->name;
            $this->bio = $author->bio ?? '';
            $this->notes = $author->notes ?? '';
        }

        protected function resetForm(): void
        {
            $this->selected = null;
            $this->reset(['name', 'bio', 'notes']);
            $this->resetErrorBag();
        }

    };
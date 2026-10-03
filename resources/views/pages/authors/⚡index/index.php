<?php

use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Title('Ledger — Authors')]
    class extends Component {

    use WithPagination;
    use HasCursorPagination;
    use HasModalCrud;

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
            ->when($this->search, fn(Builder $q) => $q->where(function (Builder $q) {
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

    public function render()
    {
        $filtered = $this->filteredQuery();

        // avgRecords is computed in PHP over the fetched collection rather
        // than via a SQL avg(), because 'records_count' is a withCount()
        // alias — an aggregate() query against it only round-trips
        // correctly through Laravel's having-wrapping path, which we're
        // deliberately not using (see filteredQuery()).
        $summary = [
            'total' => (clone $filtered)->count(),
            'avgRecords' => (int) round((clone $filtered)->get()->avg('records_count') ?? 0),
            'mostRecords' => (clone $filtered)->orderByDesc('records_count')->first(),
        ];

        $authors = $filtered
            ->orderBy($this->sortBy, $this->sortDirection)
            ->orderBy('id', $this->sortDirection) // tiebreaker: keeps cursor pagination stable when sort column has duplicates
            ->cursorPaginate(
                perPage: 10,
                cursorName: 'cursor',
                cursor: $this->cursor ? Cursor::fromEncoded($this->cursor) : null,
            );

        return $this->view([
            'authors' => $authors,
            'summary' => $summary,
        ]);
    }

};
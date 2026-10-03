<?php

use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Livewire\Concerns\HasSearchableRelations;
use App\Models\Author;
use App\Models\Record;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Title('Ledger — Records')]
    class extends Component {

    use WithPagination;
    use HasSearchableRelations;
    use HasCursorPagination;
    use HasModalCrud;

    public ?Record $selected = null;

    // Form fields
    public string $title = '';
    public string $excerpt = '';
    public string $content = '';
    public string $category = 'Historical Event';
    public string $era = 'Age of Crowns';
    public string $date = '';
    public string $importance = 'Notable';
    public bool $confidential = false;

    // Form author picker
    public string $authorId = '';
    public string $authorName = '';
    public string $authorFormSearch = '';

    public array $categories = ['Historical Event', 'Treaty', 'Mystery', 'Biography', 'War', 'Disaster', 'Foundation', 'Prophecy'];
    public array $eras = ['First Age', 'Before the Crown', 'Age of Shadows', 'Age of Ash', 'Age of Kings', 'Age of Crowns', 'Unknown'];
    public array $importances = ['Notable', 'Important', 'Critical'];

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $categoryFilter = '';

    #[Url(history: true)]
    public string $eraFilter = '';

    #[Url(history: true)]
    public string $importanceFilter = '';

    #[Url(history: true)]
    public string $confidentialFilter = ''; // '' = any, '1' = confidential only, '0' = public only

    // Filter author picker
    #[Url(history: true)]
    public string $authorFilter = '';
    public string $authorFilterName = '';
    public string $authorFilterSearch = '';

    #[Url(history: true)]
    public string $sortBy = 'title';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    /**
     * A record's credited author, used both to filter the list and to pick
     * one on the form.
     */
    protected function searchableRelations(): array
    {
        return [
            'author-filter' => [
                'model' => Author::class,
                'idProperty' => 'authorFilter',
                'nameProperty' => 'authorFilterName',
                'searchProperty' => 'authorFilterSearch',
                'label' => fn(Author $a) => $a->name,
                'resetsCursor' => true,
            ],
            'author-form' => [
                'model' => Author::class,
                'idProperty' => 'authorId',
                'nameProperty' => 'authorName',
                'searchProperty' => 'authorFormSearch',
                'label' => fn(Author $a) => $a->name,
            ],
        ];
    }

    #[Computed]
    public function authorFilterResults()
    {
        return $this->relationResults('author-filter');
    }

    #[Computed]
    public function authorFormResults()
    {
        return $this->relationResults('author-form')
            ->map(fn(Author $a) => ['id' => $a->id, 'label' => $a->name]);
    }

    public function selectAuthorFilter(string $authorId): void
    {
        $this->selectRelation('author-filter', $authorId);
    }

    public function clearAuthorFilter(): void
    {
        $this->clearRelation('author-filter');
    }

    public function selectAuthorForm(string $authorId): void
    {
        $this->selectRelation('author-form', $authorId);
    }

    public function clearAuthorForm(): void
    {
        $this->clearRelation('author-form');
    }

    public function mount(): void
    {
        $this->hydrateRelationName('author-filter');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'categoryFilter', 'eraFilter', 'importanceFilter', 'confidentialFilter']);
        $this->clearRelation('author-filter');
        $this->cursor = null;
    }

    /**
     * importance is a free-form label ('Notable'..'Critical'); the actual
     * sortable column is importance_level (see Record::booted()), since
     * alphabetical order doesn't match real significance, and
     * cursorPaginate() can't order by a raw expression — only a real
     * column works with it.
     */
    protected function sortColumn(): string
    {
        return $this->sortBy === 'importance' ? 'importance_level' : $this->sortBy;
    }

    /**
     * Shared filtered query — used both for the paginated list and the
     * summary aggregates, so the summary always reflects what's filtered,
     * not the whole table.
     */
    protected function filteredQuery(): Builder
    {
        return Record::query()
            ->when($this->search, fn(Builder $q) => $q->where(function (Builder $q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('excerpt', 'like', "%{$this->search}%")
                    ->orWhere('content', 'like', "%{$this->search}%");
            }))
            ->when($this->categoryFilter, fn(Builder $q) => $q->where('category', $this->categoryFilter))
            ->when($this->eraFilter, fn(Builder $q) => $q->where('era', $this->eraFilter))
            ->when($this->importanceFilter, fn(Builder $q) => $q->where('importance', $this->importanceFilter))
            ->when($this->confidentialFilter !== '', fn(Builder $q) => $q->where('confidential', $this->confidentialFilter === '1'))
            ->when($this->authorFilter, fn(Builder $q) => $q->where('author_id', $this->authorFilter));
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'category' => ['required', 'string', 'in:' . implode(',', $this->categories)],
            'era' => ['required', 'string', 'in:' . implode(',', $this->eras)],
            'date' => ['nullable', 'string', 'max:100'],
            'authorId' => ['nullable', 'exists:authors,id'],
            'importance' => ['required', 'string', 'in:' . implode(',', $this->importances)],
            'confidential' => ['boolean'],
        ];
    }

    public function openView(Record $record): void
    {
        $this->fillForm($record);
        $this->modalMode = 'view';
        $this->showModal = true;
    }

    public function openEdit(Record $record): void
    {
        $this->fillForm($record);
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function openDelete(Record $record): void
    {
        $this->selected = $record;
        $this->modalMode = 'delete';
        $this->showModal = true;
    }

    public function confirmDelete(): void
    {
        $this->selected?->delete();
        session()->flash('success', 'Record removed from the archive.');
        $this->closeModal();
    }

    public function save(): void
    {
        $data = $this->validate();

        $data['author_id'] = $data['authorId'] ?: null;
        unset($data['authorId']);

        if ($this->selected) {
            $this->selected->update($data);
            session()->flash('success', 'Record updated.');
        } else {
            $data['slug'] = Record::uniqueSlug($this->title);
            Record::create($data);
            session()->flash('success', 'Record added to the archive.');
        }

        $this->closeModal();
    }

    protected function fillForm(Record $record): void
    {
        $this->selected = $record->load('author');
        $this->title = $record->title;
        $this->excerpt = $record->excerpt ?? '';
        $this->content = $record->content ?? '';
        $this->category = $record->category;
        $this->era = $record->era;
        $this->date = $record->date ?? '';
        $this->importance = $record->importance;
        $this->confidential = $record->confidential;
        $this->authorId = (string) ($record->author_id ?? '');
        $this->authorName = $record->author?->name ?? '';
    }

    protected function resetForm(): void
    {
        $this->selected = null;
        $this->reset(['title', 'excerpt', 'content', 'date']);
        $this->category = $this->categories[0];
        $this->era = $this->eras[0];
        $this->importance = $this->importances[0];
        $this->confidential = false;
        $this->clearRelation('author-form');
        $this->resetErrorBag();
    }

    public function render()
    {
        $filtered = $this->filteredQuery();

        $summary = [
            'total' => (clone $filtered)->count(),
            'confidentialCount' => (clone $filtered)->where('confidential', true)->count(),
            'mostSignificant' => (clone $filtered)
                ->orderByDesc('importance_level')
                ->orderByDesc('id')
                ->first(),
        ];

        $records = $filtered
            ->with('author')
            ->orderBy($this->sortColumn(), $this->sortDirection)
            ->orderBy('id', $this->sortDirection) // tiebreaker: keeps cursor pagination stable when sort column has duplicates
            ->cursorPaginate(
                perPage: 10,
                cursorName: 'cursor',
                cursor: $this->cursor ? Cursor::fromEncoded($this->cursor) : null,
            );

        return $this->view([
            'records' => $records,
            'summary' => $summary,
        ]);
    }

};
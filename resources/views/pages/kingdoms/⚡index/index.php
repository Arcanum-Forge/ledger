<?php

use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Livewire\Concerns\HasSearchableRelations;
use App\Models\Kingdom;
use App\Models\Region;
use App\Models\Ruler;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Livewire\Attributes\Computed;

new
    #[Title('Ledger — Kingdoms')]
    class extends Component {

    use WithPagination;
    use HasSearchableRelations;
    use HasCursorPagination;
    use HasModalCrud;

    public ?Kingdom $selected = null;

    // Form fields
    public string $name = '';
    public string $title = '';
    public string $description = '';
    public string $population = '';
    public string $alignment = 'Neutral';
    public int $threat = 0;
    public string $founded = '';

    public array $alignments = ['Lawful', 'Neutral', 'Chaotic', 'Good', 'Evil'];

    // Form relation pickers
    public string $regionId = '';
    public string $rulerId = '';
    public string $rulerName = '';
    public string $regionName = '';
    public string $rulerFormSearch = '';
    public string $regionFormSearch = '';

    // Filter relation pickers
    public string $rulerSearch = '';
    public string $regionSearch = '';
    public string $rulerFilterName = '';
    public string $regionFilterName = '';

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $alignmentFilter = '';

    #[Url(history: true)]
    public string $regionFilter = '';

    #[Url(history: true)]
    public string $rulerFilter = '';

    #[Url(history: true)]
    public int $minThreat = 0;

    #[Url(history: true)]
    public int $maxThreat = 100;

    #[Url(history: true)]
    public string $sortBy = 'name';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    /**
     * The four searchable pickers this component needs. Adding a fifth
     * (e.g. a faction picker) is one array entry, not another ~20 lines.
     */
    protected function searchableRelations(): array
    {
        return [
            'ruler-filter' => [
                'model' => Ruler::class,
                'idProperty' => 'rulerFilter',
                'nameProperty' => 'rulerFilterName',
                'searchProperty' => 'rulerSearch',
                'label' => fn(Ruler $r) => $r->name,
                'resetsCursor' => true,
            ],
            'region-filter' => [
                'model' => Region::class,
                'idProperty' => 'regionFilter',
                'nameProperty' => 'regionFilterName',
                'searchProperty' => 'regionSearch',
                'label' => fn(Region $r) => $r->name,
                'resetsCursor' => true,
            ],
            'ruler-form' => [
                'model' => Ruler::class,
                'idProperty' => 'rulerId',
                'nameProperty' => 'rulerName',
                'searchProperty' => 'rulerFormSearch',
                'label' => fn(Ruler $r) => $r->full_title,
            ],
            'region-form' => [
                'model' => Region::class,
                'idProperty' => 'regionId',
                'nameProperty' => 'regionName',
                'searchProperty' => 'regionFormSearch',
                'label' => fn(Region $r) => $r->name,
            ],
        ];
    }


    #[Computed]
    public function rulerResults()
    {
        return $this->relationResults('ruler-filter');
    }

    #[Computed]
    public function regionResults()
    {
        return $this->relationResults('region-filter');
    }

    #[Computed]
    public function rulerFormResults()
    {
        return $this->relationResults('ruler-form')
            ->map(fn(Ruler $r) => ['id' => $r->id, 'label' => $r->full_title]);
    }

    #[Computed]
    public function regionFormResults()
    {
        return $this->relationResults('region-form')
            ->map(fn(Region $r) => ['id' => $r->id, 'label' => $r->name]);
    }

    public function selectRulerFilter(string $rulerId): void
    {
        $this->selectRelation('ruler-filter', $rulerId);
    }

    public function clearRulerFilter(): void
    {
        $this->clearRelation('ruler-filter');
    }

    public function selectRegionFilter(string $regionId): void
    {
        $this->selectRelation('region-filter', $regionId);
    }

    public function clearRegionFilter(): void
    {
        $this->clearRelation('region-filter');
    }

    public function selectRulerForm(string $rulerId): void
    {
        $this->selectRelation('ruler-form', $rulerId);
    }

    public function clearRulerForm(): void
    {
        $this->clearRelation('ruler-form');
    }

    public function selectRegionForm(string $regionId): void
    {
        $this->selectRelation('region-form', $regionId);
    }

    public function clearRegionForm(): void
    {
        $this->clearRelation('region-form');
    }

    public function mount(): void
    {
        $this->hydrateRelationName('ruler-filter');
        $this->hydrateRelationName('region-filter');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'alignmentFilter', 'regionFilter', 'rulerFilter', 'minThreat', 'maxThreat']);
        $this->clearRelation('ruler-filter');
        $this->clearRelation('region-filter');
        $this->cursor = null;
    }

    /**
     * Shared filtered query — used both for the paginated list and the
     * summary aggregates, so the summary always reflects what's filtered,
     * not the whole table.
     */
    protected function filteredQuery(): Builder
    {
        return Kingdom::query()
            ->when($this->search, fn(Builder $q) => $q->where(function (Builder $q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('title', 'like', "%{$this->search}%");
            }))
            ->when($this->alignmentFilter, fn(Builder $q) => $q->where('alignment', $this->alignmentFilter))
            ->when($this->regionFilter, fn(Builder $q) => $q->where('region_id', $this->regionFilter))
            ->when($this->rulerFilter, fn(Builder $q) => $q->where('ruler_id', $this->rulerFilter))
            ->where('threat', '>=', $this->minThreat)
            ->where('threat', '<=', $this->maxThreat);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'population' => ['required', 'string', 'max:100'],
            'alignment' => ['required', 'string', 'in:' . implode(',', $this->alignments)],
            'threat' => ['required', 'integer', 'min:0', 'max:100'],
            'founded' => ['required', 'string', 'max:100'],
            'regionId' => ['nullable', 'exists:regions,id'],
            'rulerId' => ['nullable', 'exists:rulers,id'],
        ];
    }

    public function openView(Kingdom $kingdom): void
    {
        $this->fillForm($kingdom);
        $this->modalMode = 'view';
        $this->showModal = true;
    }

    public function openEdit(Kingdom $kingdom): void
    {
        $this->fillForm($kingdom);
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function openDelete(Kingdom $kingdom): void
    {
        $this->selected = $kingdom;
        $this->modalMode = 'delete';
        $this->showModal = true;
    }

    public function confirmDelete(): void
    {
        $this->selected?->delete();

        session()->flash('success', 'Kingdom record removed from the archive.');

        $this->closeModal();
    }

    public function save(): void
    {
        $data = $this->validate();

        $data['region_id'] = $data['regionId'] ?: null;
        $data['ruler_id'] = $data['rulerId'] ?: null;
        unset($data['regionId'], $data['rulerId']);

        if ($this->selected) {
            $this->selected->update($data);
            session()->flash('success', 'Kingdom record updated.');
        } else {
            $data['slug'] = Kingdom::uniqueSlug($this->name);
            Kingdom::create($data);
            session()->flash('success', 'Kingdom record added to the archive.');
        }

        $this->closeModal();
    }

    protected function fillForm(Kingdom $kingdom): void
    {
        $this->selected = $kingdom;
        $this->name = $kingdom->name;
        $this->title = $kingdom->title;
        $this->description = $kingdom->description;
        $this->population = $kingdom->population;
        $this->alignment = $kingdom->alignment;
        $this->threat = $kingdom->threat;
        $this->founded = $kingdom->founded;
        $this->regionId = (string) ($kingdom->region_id ?? '');
        $this->rulerId = (string) ($kingdom->ruler_id ?? '');
        $this->regionName = $kingdom->region?->name ?? '';
        $this->rulerName = $kingdom->ruler?->full_title ?? '';
    }

    protected function resetForm(): void
    {
        $this->selected = null;
        $this->reset(['name', 'title', 'description', 'population', 'founded']);
        $this->alignment = 'Neutral';
        $this->threat = 0;
        $this->clearRelation('ruler-form');
        $this->clearRelation('region-form');
        $this->resetErrorBag();
    }

    public function render()
    {
        $filtered = $this->filteredQuery();

        // Summary aggregates run against the filtered set, cloned so the
        // aggregate queries don't mutate the query object the list uses below.
        $summary = [
            'total' => (clone $filtered)->count(),
            'avgThreat' => (int) round((clone $filtered)->avg('threat') ?? 0),
            'highest' => (clone $filtered)->orderByDesc('threat')->first(),
        ];

        $kingdoms = $filtered
            ->orderBy($this->sortBy, $this->sortDirection)
            ->orderBy('id', $this->sortDirection) // tiebreaker: keeps cursor pagination stable when sort column has duplicates
            ->cursorPaginate(
                perPage: 10,
                cursorName: 'cursor',
                cursor: $this->cursor ? Cursor::fromEncoded($this->cursor) : null,
            );

        return $this->view([
            'kingdoms' => $kingdoms,
            'summary' => $summary,
        ]);
    }

};
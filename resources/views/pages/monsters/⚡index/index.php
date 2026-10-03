<?php

use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Livewire\Concerns\HasSearchableRelations;
use App\Models\Kingdom;
use App\Models\Monster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Title('Ledger — Monsters')]
    class extends Component {

    use WithPagination;
    use HasSearchableRelations;
    use HasCursorPagination;
    use HasModalCrud;

    public ?Monster $selected = null;

    // Form fields
    public string $name = '';
    public string $classification = 'Beast';
    public string $habitat = 'Forest';
    public string $threat = 'Moderate';
    public int $sightings = 0;
    public string $status = 'Common';
    public string $description = '';

    // Form kingdom (origin) picker
    public string $kingdomId = '';
    public string $kingdomName = '';
    public string $kingdomFormSearch = '';

    public array $classifications = ['Beast', 'Dragonkin', 'Undead', 'Aberration', 'Giant', 'Unknown'];
    public array $habitats = ['Forest', 'Mountains', 'Ruins', 'Swamp', 'Woodland', 'River', 'Unknown'];
    public array $threats = ['Low', 'Moderate', 'High', 'Extreme'];
    public array $statuses = ['Common', 'Rare', 'Active', 'Territorial', 'Unconfirmed'];

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $classificationFilter = '';

    #[Url(history: true)]
    public string $habitatFilter = '';

    #[Url(history: true)]
    public string $threatFilter = '';

    // Filter kingdom (origin) picker
    #[Url(history: true)]
    public string $kingdomFilter = '';
    public string $kingdomFilterName = '';
    public string $kingdomFilterSearch = '';

    #[Url(history: true)]
    public int $minSightings = 0;

    #[Url(history: true)]
    public int $maxSightings = 150;

    #[Url(history: true)]
    public string $sortBy = 'name';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    /**
     * A monster's confirmed origin (Kingdom), used both to filter the list
     * and to pick one on the form.
     */
    protected function searchableRelations(): array
    {
        return [
            'kingdom-filter' => [
                'model' => Kingdom::class,
                'idProperty' => 'kingdomFilter',
                'nameProperty' => 'kingdomFilterName',
                'searchProperty' => 'kingdomFilterSearch',
                'label' => fn(Kingdom $k) => $k->name,
                'resetsCursor' => true,
            ],
            'kingdom-form' => [
                'model' => Kingdom::class,
                'idProperty' => 'kingdomId',
                'nameProperty' => 'kingdomName',
                'searchProperty' => 'kingdomFormSearch',
                'label' => fn(Kingdom $k) => $k->name,
            ],
        ];
    }

    #[Computed]
    public function kingdomFilterResults()
    {
        return $this->relationResults('kingdom-filter');
    }

    #[Computed]
    public function kingdomFormResults()
    {
        return $this->relationResults('kingdom-form')
            ->map(fn(Kingdom $k) => ['id' => $k->id, 'label' => $k->name]);
    }

    public function selectKingdomFilter(string $kingdomId): void
    {
        $this->selectRelation('kingdom-filter', $kingdomId);
    }

    public function clearKingdomFilter(): void
    {
        $this->clearRelation('kingdom-filter');
    }

    public function selectKingdomForm(string $kingdomId): void
    {
        $this->selectRelation('kingdom-form', $kingdomId);
    }

    public function clearKingdomForm(): void
    {
        $this->clearRelation('kingdom-form');
    }

    public function mount(): void
    {
        $this->hydrateRelationName('kingdom-filter');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'classificationFilter', 'habitatFilter', 'threatFilter', 'minSightings', 'maxSightings']);
        $this->clearRelation('kingdom-filter');
        $this->cursor = null;
    }


    /**
     * threat is a free-form label ('Low'..'Extreme'); the actual sortable
     * column is threat_level (see Monster::booted()), since 'Extreme'
     * sorting before 'High' alphabetically is backwards from real
     * severity, and cursorPaginate() can't order by a raw expression —
     * only a real column works with it.
     */
    protected function sortColumn(): string
    {
        return $this->sortBy === 'threat' ? 'threat_level' : $this->sortBy;
    }

    /**
     * Shared filtered query — used both for the paginated list and the
     * summary aggregates, so the summary always reflects what's filtered,
     * not the whole table.
     */
    protected function filteredQuery(): Builder
    {
        return Monster::query()
            ->when($this->search, fn(Builder $q) => $q->where(function (Builder $q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }))
            ->when($this->classificationFilter, fn(Builder $q) => $q->where('classification', $this->classificationFilter))
            ->when($this->habitatFilter, fn(Builder $q) => $q->where('habitat', $this->habitatFilter))
            ->when($this->threatFilter, fn(Builder $q) => $q->where('threat', $this->threatFilter))
            ->when($this->kingdomFilter, fn(Builder $q) => $q->where('kingdom_id', $this->kingdomFilter))
            ->where('sightings', '>=', $this->minSightings)
            ->where('sightings', '<=', $this->maxSightings);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'classification' => ['required', 'string', 'in:' . implode(',', $this->classifications)],
            'habitat' => ['required', 'string', 'in:' . implode(',', $this->habitats)],
            'kingdomId' => ['nullable', 'exists:kingdoms,id'],
            'threat' => ['required', 'string', 'in:' . implode(',', $this->threats)],
            'sightings' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:' . implode(',', $this->statuses)],
            'description' => ['nullable', 'string'],
        ];
    }

    public function openView(Monster $monster): void
    {
        $this->fillForm($monster);
        $this->modalMode = 'view';
        $this->showModal = true;
    }

    public function openEdit(Monster $monster): void
    {
        $this->fillForm($monster);
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function openDelete(Monster $monster): void
    {
        $this->selected = $monster;
        $this->modalMode = 'delete';
        $this->showModal = true;
    }

    public function confirmDelete(): void
    {
        $this->selected?->delete();

        session()->flash('success', 'Monster record removed from the archive.');

        $this->closeModal();
    }

    public function save(): void
    {
        $data = $this->validate();

        $data['kingdom_id'] = $data['kingdomId'] ?: null;
        unset($data['kingdomId']);

        if ($this->selected) {
            $this->selected->update($data);
            session()->flash('success', 'Monster record updated.');

        } else {
            $data['slug'] = Monster::uniqueSlug($this->name);
            Monster::create($data);
            session()->flash('success', 'Monster record added to the archive.');
        }

        $this->closeModal();
    }

    protected function fillForm(Monster $monster): void
    {
        $this->selected = $monster->load('kingdom');
        $this->name = $monster->name;
        $this->classification = $monster->classification;
        $this->habitat = $monster->habitat;
        $this->threat = $monster->threat;
        $this->sightings = $monster->sightings;
        $this->status = $monster->status;
        $this->description = $monster->description ?? '';
        $this->kingdomId = (string) ($monster->kingdom_id ?? '');
        $this->kingdomName = $monster->kingdom?->name ?? '';
    }

    protected function resetForm(): void
    {
        $this->selected = null;
        $this->reset(['name', 'description']);
        $this->classification = $this->classifications[0];
        $this->habitat = $this->habitats[0];
        $this->threat = $this->threats[1]; // Moderate
        $this->sightings = 0;
        $this->status = $this->statuses[0];
        $this->clearRelation('kingdom-form');
        $this->resetErrorBag();
    }

    public function render()
    {
        $filtered = $this->filteredQuery();

        $summary = [
            'total' => (clone $filtered)->count(),
            'avgSightings' => (int) round((clone $filtered)->avg('sightings') ?? 0),
            'mostThreatening' => (clone $filtered)
                ->orderByDesc('threat_level')
                ->orderByDesc('sightings')
                ->first(),
        ];

        $monsters = $filtered
            ->with('kingdom')
            ->orderBy($this->sortColumn(), $this->sortDirection)
            ->orderBy('id', $this->sortDirection) // tiebreaker: keeps cursor pagination stable when sort column has duplicates
            ->cursorPaginate(
                perPage: 10,
                cursorName: 'cursor',
                cursor: $this->cursor ? Cursor::fromEncoded($this->cursor) : null,
            );

        return $this->view([
            'monsters' => $monsters,
            'summary' => $summary,
        ]);
    }

};
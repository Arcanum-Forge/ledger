<?php

use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Livewire\Concerns\HasSearchableRelations;
use App\Models\Kingdom;
use App\Models\Region;
use App\Models\ThreatReport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Title('Ledger — Threat Reports')]
    class extends Component {

    use WithPagination;
    use HasSearchableRelations;
    use HasCursorPagination;
    use HasModalCrud;

    public ?ThreatReport $selected = null;

    // Form fields
    public string $reportNumber = '';
    public string $title = '';
    public string $type = 'Military';
    public string $level = 'Elevated';
    public string $status = 'Active';
    public int $sightings = 0;
    public string $description = '';

    // Form pickers
    public string $regionId = '';
    public string $regionName = '';
    public string $regionFormSearch = '';

    public string $kingdomId = '';
    public string $kingdomName = '';
    public string $kingdomFormSearch = '';

    public array $types = ['Military', 'Monster', 'Undead', 'Dragon', 'Criminal', 'Unknown'];
    public array $levels = ['Elevated', 'Severe', 'Critical', 'Moderate'];
    public array $statuses = ['Active', 'Investigating', 'Contained', 'Unconfirmed', 'Resolved'];

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $typeFilter = '';

    #[Url(history: true)]
    public string $levelFilter = '';

    #[Url(history: true)]
    public string $statusFilter = '';

    // Filter pickers
    #[Url(history: true)]
    public string $regionFilter = '';
    public string $regionFilterName = '';
    public string $regionFilterSearch = '';

    #[Url(history: true)]
    public string $kingdomFilter = '';
    public string $kingdomFilterName = '';
    public string $kingdomFilterSearch = '';

    #[Url(history: true)]
    public int $minSightings = 0;

    #[Url(history: true)]
    public int $maxSightings = 100;

    #[Url(history: true)]
    public string $sortBy = 'report_number';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    /**
     * A report's location (Region) and the Kingdom it falls under — the
     * same two-relation shape Kingdom itself uses for Ruler+Region.
     */
    protected function searchableRelations(): array
    {
        return [
            'region-filter' => [
                'model' => Region::class,
                'idProperty' => 'regionFilter',
                'nameProperty' => 'regionFilterName',
                'searchProperty' => 'regionFilterSearch',
                'label' => fn(Region $r) => $r->name,
                'resetsCursor' => true,
            ],
            'region-form' => [
                'model' => Region::class,
                'idProperty' => 'regionId',
                'nameProperty' => 'regionName',
                'searchProperty' => 'regionFormSearch',
                'label' => fn(Region $r) => $r->name,
            ],
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
    public function regionFilterResults()
    {
        return $this->relationResults('region-filter');
    }

    #[Computed]
    public function regionFormResults()
    {
        return $this->relationResults('region-form')
            ->map(fn(Region $r) => ['id' => $r->id, 'label' => $r->name]);
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

    public function selectRegionFilter(string $regionId): void
    {
        $this->selectRelation('region-filter', $regionId);
    }

    public function clearRegionFilter(): void
    {
        $this->clearRelation('region-filter');
    }

    public function selectRegionForm(string $regionId): void
    {
        $this->selectRelation('region-form', $regionId);
    }

    public function clearRegionForm(): void
    {
        $this->clearRelation('region-form');
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
        $this->hydrateRelationName('region-filter');
        $this->hydrateRelationName('kingdom-filter');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'typeFilter', 'levelFilter', 'statusFilter', 'minSightings', 'maxSightings']);
        $this->clearRelation('region-filter');
        $this->clearRelation('kingdom-filter');
        $this->cursor = null;
    }

    /**
     * level is a free-form label ('Elevated'..'Critical'); the actual
     * sortable column is level_severity (see ThreatReport::booted()),
     * since alphabetical order doesn't match real severity, and
     * cursorPaginate() can't order by a raw expression — only a real
     * column works with it.
     */
    protected function sortColumn(): string
    {
        return $this->sortBy === 'level' ? 'level_severity' : $this->sortBy;
    }

    /**
     * Shared filtered query — used both for the paginated list and the
     * summary aggregates, so the summary always reflects what's filtered,
     * not the whole table.
     */
    protected function filteredQuery(): Builder
    {
        return ThreatReport::query()
            ->when($this->search, fn(Builder $q) => $q->where(function (Builder $q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('report_number', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }))
            ->when($this->typeFilter, fn(Builder $q) => $q->where('type', $this->typeFilter))
            ->when($this->levelFilter, fn(Builder $q) => $q->where('level', $this->levelFilter))
            ->when($this->statusFilter, fn(Builder $q) => $q->where('status', $this->statusFilter))
            ->when($this->regionFilter, fn(Builder $q) => $q->where('region_id', $this->regionFilter))
            ->when($this->kingdomFilter, fn(Builder $q) => $q->where('kingdom_id', $this->kingdomFilter))
            ->where('sightings', '>=', $this->minSightings)
            ->where('sightings', '<=', $this->maxSightings);
    }

    protected function rules(): array
    {
        return [
            'reportNumber' => ['required', 'string', 'max:20'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:' . implode(',', $this->types)],
            'level' => ['required', 'string', 'in:' . implode(',', $this->levels)],
            'status' => ['required', 'string', 'in:' . implode(',', $this->statuses)],
            'sightings' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'regionId' => ['nullable', 'exists:regions,id'],
            'kingdomId' => ['nullable', 'exists:kingdoms,id'],
        ];
    }

    public function openView(ThreatReport $threatReport): void
    {
        $this->fillForm($threatReport);
        $this->modalMode = 'view';
        $this->showModal = true;
    }

    public function openEdit(ThreatReport $threatReport): void
    {
        $this->fillForm($threatReport);
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function openDelete(ThreatReport $threatReport): void
    {
        $this->selected = $threatReport;
        $this->modalMode = 'delete';
        $this->showModal = true;
    }

    public function confirmDelete(): void
    {
        $this->selected?->delete();
        session()->flash('success', 'Threat report removed from the archive.');
        $this->closeModal();
    }

    public function save(): void
    {
        $data = $this->validate();

        $data['report_number'] = $data['reportNumber'];
        $data['region_id'] = $data['regionId'] ?: null;
        $data['kingdom_id'] = $data['kingdomId'] ?: null;
        unset($data['reportNumber'], $data['regionId'], $data['kingdomId']);

        if ($this->selected) {
            $this->selected->update($data);
            session()->flash('success', 'Threat report updated.');
        } else {
            $data['slug'] = ThreatReport::uniqueSlug($this->title);
            ThreatReport::create($data);
            session()->flash('success', 'Threat report added to the archive.');
        }

        $this->closeModal();
    }

    protected function fillForm(ThreatReport $threatReport): void
    {
        $this->selected = $threatReport->load(['region', 'kingdom']);
        $this->reportNumber = $threatReport->report_number;
        $this->title = $threatReport->title;
        $this->type = $threatReport->type;
        $this->level = $threatReport->level;
        $this->status = $threatReport->status;
        $this->sightings = $threatReport->sightings;
        $this->description = $threatReport->description ?? '';
        $this->regionId = (string) ($threatReport->region_id ?? '');
        $this->regionName = $threatReport->region?->name ?? '';
        $this->kingdomId = (string) ($threatReport->kingdom_id ?? '');
        $this->kingdomName = $threatReport->kingdom?->name ?? '';
    }

    protected function resetForm(): void
    {
        $this->selected = null;
        $this->reset(['reportNumber', 'title', 'description']);
        $this->type = $this->types[0];
        $this->level = $this->levels[0];
        $this->status = $this->statuses[0];
        $this->sightings = 0;
        $this->clearRelation('region-form');
        $this->clearRelation('kingdom-form');
        $this->resetErrorBag();
    }

    public function render()
    {
        $filtered = $this->filteredQuery();

        $summary = [
            'total' => (clone $filtered)->count(),
            'avgSightings' => (int) round((clone $filtered)->avg('sightings') ?? 0),
            'mostSevere' => (clone $filtered)
                ->orderByDesc('level_severity')
                ->orderByDesc('sightings')
                ->first(),
        ];

        $reports = $filtered
            ->with(['region', 'kingdom'])
            ->orderBy($this->sortColumn(), $this->sortDirection)
            ->orderBy('id', $this->sortDirection) // tiebreaker: keeps cursor pagination stable when sort column has duplicates
            ->cursorPaginate(
                perPage: 10,
                cursorName: 'cursor',
                cursor: $this->cursor ? Cursor::fromEncoded($this->cursor) : null,
            );

        return $this->view([
            'reports' => $reports,
            'summary' => $summary,
        ]);
    }

};
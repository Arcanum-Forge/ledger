<?php

use App\Livewire\Concerns\HasCursorPagination;
use App\Livewire\Concerns\HasModalCrud;
use App\Livewire\Concerns\HasSearchableRelations;
use App\Models\Faction;
use App\Models\Kingdom;
use App\Models\Leader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Title('Ledger — Factions')]
    class extends Component {

    use WithPagination;
    use HasSearchableRelations;
    use HasCursorPagination;
    use HasModalCrud;

    public ?Faction $selected = null;

    // Form fields
    public string $name = '';
    public string $title = '';
    public string $description = '';
    public string $type = 'Knightly Order';
    public string $members = '';
    public string $alignment = 'Neutral';
    public int $influence = 0;
    public string $status = 'Active';
    public string $statusDescription = '';

    // Form headquarters (kingdom) picker
    public string $kingdomId = '';
    public string $kingdomName = '';
    public string $kingdomFormSearch = '';

    // Form leader picker
    public string $leaderId = '';
    public string $leaderName = '';
    public string $leaderFormSearch = '';

    public array $types = ['Knightly Order', 'Merchant Guild', 'Secret Society', 'Cult', 'Druidic Circle', 'Mercenary Company'];
    public array $alignments = ['Lawful Good', 'Neutral Good', 'Chaotic Good', 'Lawful Neutral', 'Neutral', 'Chaotic Neutral', 'Lawful Evil', 'Neutral Evil', 'Chaotic Evil'];
    public array $statuses = ['Active', 'Expanding', 'Stable', 'Contracted', 'Hostile', 'Unconfirmed'];

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $alignmentFilter = '';

    // Filter headquarters (kingdom) picker
    #[Url(history: true)]
    public string $kingdomFilter = '';
    public string $kingdomFilterName = '';
    public string $kingdomFilterSearch = '';

    // Filter leader picker
    #[Url(history: true)]
    public string $leaderFilter = '';
    public string $leaderFilterName = '';
    public string $leaderFilterSearch = '';

    #[Url(history: true)]
    public int $minInfluence = 0;

    #[Url(history: true)]
    public int $maxInfluence = 100;

    #[Url(history: true)]
    public string $sortBy = 'name';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    /**
     * A faction has two relations — its headquarters (Kingdom) and its
     * leader (Leader) — each used both to filter the list and to pick one
     * on the form.
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
            'leader-filter' => [
                'model' => Leader::class,
                'idProperty' => 'leaderFilter',
                'nameProperty' => 'leaderFilterName',
                'searchProperty' => 'leaderFilterSearch',
                'label' => fn(Leader $l) => $l->name,
                'resetsCursor' => true,
            ],
            'leader-form' => [
                'model' => Leader::class,
                'idProperty' => 'leaderId',
                'nameProperty' => 'leaderName',
                'searchProperty' => 'leaderFormSearch',
                'label' => fn(Leader $l) => $l->name,
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

    #[Computed]
    public function leaderFilterResults()
    {
        return $this->relationResults('leader-filter');
    }

    #[Computed]
    public function leaderFormResults()
    {
        return $this->relationResults('leader-form')
            ->map(fn(Leader $l) => ['id' => $l->id, 'label' => $l->name]);
    }

    public function selectLeaderFilter(string $leaderId): void
    {
        $this->selectRelation('leader-filter', $leaderId);
    }

    public function clearLeaderFilter(): void
    {
        $this->clearRelation('leader-filter');
    }

    public function selectLeaderForm(string $leaderId): void
    {
        $this->selectRelation('leader-form', $leaderId);
    }

    public function clearLeaderForm(): void
    {
        $this->clearRelation('leader-form');
    }

    public function mount(): void
    {
        $this->hydrateRelationName('kingdom-filter');
        $this->hydrateRelationName('leader-filter');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'alignmentFilter', 'minInfluence', 'maxInfluence']);
        $this->clearRelation('kingdom-filter');
        $this->clearRelation('leader-filter');
        $this->cursor = null;
    }

    /**
     * Shared filtered query — used both for the paginated list and the
     * summary aggregates, so the summary always reflects what's filtered,
     * not the whole table.
     */
    protected function filteredQuery(): Builder
    {
        return Faction::query()
            ->when($this->search, fn(Builder $q) => $q->where(function (Builder $q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('title', 'like', "%{$this->search}%")
                    ->orWhereHas('leader', fn(Builder $q) => $q->where('name', 'like', "%{$this->search}%"));
            }))
            ->when($this->alignmentFilter, fn(Builder $q) => $q->where('alignment', $this->alignmentFilter))
            ->when($this->kingdomFilter, fn(Builder $q) => $q->where('kingdom_id', $this->kingdomFilter))
            ->when($this->leaderFilter, fn(Builder $q) => $q->where('leader_id', $this->leaderFilter))
            ->where('influence', '>=', $this->minInfluence)
            ->where('influence', '<=', $this->maxInfluence);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:' . implode(',', $this->types)],
            'leaderId' => ['nullable', 'exists:leaders,id'],
            'kingdomId' => ['nullable', 'exists:kingdoms,id'],
            'members' => ['nullable', 'string', 'max:100'],
            'alignment' => ['required', 'string', 'in:' . implode(',', $this->alignments)],
            'influence' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', 'string', 'in:' . implode(',', $this->statuses)],
            'statusDescription' => ['nullable', 'string'],
        ];
    }

    public function openView(Faction $faction): void
    {
        $this->fillForm($faction);
        $this->modalMode = 'view';
        $this->showModal = true;
    }

    public function openEdit(Faction $faction): void
    {
        $this->fillForm($faction);
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function openDelete(Faction $faction): void
    {
        $this->selected = $faction;
        $this->modalMode = 'delete';
        $this->showModal = true;
    }

    public function confirmDelete(): void
    {
        $this->selected?->delete();

        session()->flash('success', 'Faction record removed from the archive.');

        $this->closeModal();
    }

    public function save(): void
    {
        $data = $this->validate();

        $data['kingdom_id'] = $data['kingdomId'] ?: null;
        $data['leader_id'] = $data['leaderId'] ?: null;
        $data['status_description'] = $data['statusDescription'];
        unset($data['kingdomId'], $data['leaderId'], $data['statusDescription']);

        if ($this->selected) {
            $this->selected->update($data);
            session()->flash('success', 'Faction record updated.');
        } else {
            $data['slug'] = Faction::uniqueSlug($this->name);
            Faction::create($data);
            session()->flash('success', 'Faction record added to the archive.');
        }

        $this->closeModal();
    }

    protected function fillForm(Faction $faction): void
    {
        $this->selected = $faction->load(['kingdom', 'leader']);
        $this->name = $faction->name;
        $this->title = $faction->title;
        $this->description = $faction->description ?? '';
        $this->type = $faction->type;
        $this->members = $faction->members ?? '';
        $this->alignment = $faction->alignment;
        $this->influence = $faction->influence;
        $this->status = $faction->status;
        $this->statusDescription = $faction->status_description ?? '';
        $this->kingdomId = (string) ($faction->kingdom_id ?? '');
        $this->kingdomName = $faction->kingdom?->name ?? '';
        $this->leaderId = (string) ($faction->leader_id ?? '');
        $this->leaderName = $faction->leader?->name ?? '';
    }

    protected function resetForm(): void
    {
        $this->selected = null;
        $this->reset(['name', 'title', 'description', 'members', 'statusDescription']);
        $this->type = $this->types[0];
        $this->alignment = 'Neutral';
        $this->influence = 0;
        $this->status = $this->statuses[0];
        $this->clearRelation('kingdom-form');
        $this->clearRelation('leader-form');
        $this->resetErrorBag();
    }

    public function render()
    {
        $filtered = $this->filteredQuery();

        $summary = [
            'total' => (clone $filtered)->count(),
            'avgInfluence' => (int) round((clone $filtered)->avg('influence') ?? 0),
            'mostInfluential' => (clone $filtered)->orderByDesc('influence')->first(),
        ];

        $factions = $filtered
            ->with(['kingdom', 'leader'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->orderBy('id', $this->sortDirection) // tiebreaker: keeps cursor pagination stable when sort column has duplicates
            ->cursorPaginate(
                perPage: 10,
                cursorName: 'cursor',
                cursor: $this->cursor ? Cursor::fromEncoded($this->cursor) : null,
            );

        return $this->view([
            'factions' => $factions,
            'summary' => $summary,
        ]);
    }

};
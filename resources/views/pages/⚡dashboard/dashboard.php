<?php

use App\Models\Author;
use App\Models\Faction;
use App\Models\Kingdom;
use App\Models\Leader;
use App\Models\Monster;
use App\Models\Record;
use App\Models\Region;
use App\Models\Ruler;
use App\Models\ThreatReport;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
    #[Title('Ledger - Dashboard')]
    #[Layout('layouts.app')]
    class extends Component
    {
        #[Computed]
        public function stats(): array
        {
            // sleep(3);

            return [
                ['label' => 'Kingdoms', 'count' => Kingdom::count(), 'href' => '/kingdoms'],
                ['label' => 'Regions', 'count' => Region::count(), 'href' => '/regions'],
                ['label' => 'Rulers', 'count' => Ruler::count(), 'href' => '/rulers'],
                ['label' => 'Factions', 'count' => Faction::count(), 'href' => '/factions'],
                ['label' => 'Leaders', 'count' => Leader::count(), 'href' => '/leaders'],
                ['label' => 'Monsters', 'count' => Monster::count(), 'href' => '/monsters'],
                ['label' => 'Authors', 'count' => Author::count(), 'href' => '/authors'],
                ['label' => 'Records', 'count' => Record::count(), 'href' => '/records'],
                ['label' => 'Threat Reports', 'count' => ThreatReport::count(), 'href' => '/threat-reports'],
                ['label' => 'Users', 'count' => User::count(), 'href' => '/users'],
            ];
        }

        #[Computed]
        public function activeThreats(): Collection
        {
            // sleep(5);

            return ThreatReport::query()
                ->with(['region', 'kingdom'])
                ->whereIn('status', ['Active', 'Investigating'])
                ->orderByDesc('level_severity')
                ->orderByDesc('sightings')
                ->limit(5)
                ->get();
        }

        #[Computed]
        public function dangerousMonsters(): Collection
        {
            // sleep(7);

            return Monster::query()
                ->with('kingdom')
                ->orderByDesc('threat_level')
                ->orderByDesc('sightings')
                ->limit(5)
                ->get();
        }

        #[Computed]
        public function recentRecords(): Collection
        {
            // sleep(9);

            return Record::query()
                ->with('author')
                ->latest()
                ->limit(5)
                ->get();
        }

        #[Computed]
        public function risingFactions(): Collection
        {
            // sleep(11);

            return Faction::query()
                ->with(['kingdom', 'leader'])
                ->orderByDesc('influence')
                ->limit(5)
                ->get();
        }

        #[Computed]
        public function highestThreatKingdom(): ?Kingdom
        {
            // sleep(13);

            return Kingdom::query()
                ->with('ruler')
                ->orderByDesc('threat')
                ->first();
        }
    };

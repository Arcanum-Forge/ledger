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
use Livewire\Component;

new class extends Component {

    public function render()
    {
        $stats = [
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

        $activeThreats = ThreatReport::query()
            ->with(['region', 'kingdom'])
            ->whereIn('status', ['Active', 'Investigating'])
            ->orderByDesc('level_severity')
            ->orderByDesc('sightings')
            ->limit(5)
            ->get();

        $dangerousMonsters = Monster::query()
            ->with('kingdom')
            ->orderByDesc('threat_level')
            ->orderByDesc('sightings')
            ->limit(5)
            ->get();

        $recentRecords = Record::query()
            ->with('author')
            ->latest()
            ->limit(5)
            ->get();

        $risingFactions = Faction::query()
            ->with(['kingdom', 'leader'])
            ->orderByDesc('influence')
            ->limit(5)
            ->get();

        $highestThreatKingdom = Kingdom::query()
            ->with('ruler')
            ->orderByDesc('threat')
            ->first();

        return $this->view([
            'stats' => $stats,
            'activeThreats' => $activeThreats,
            'dangerousMonsters' => $dangerousMonsters,
            'recentRecords' => $recentRecords,
            'risingFactions' => $risingFactions,
            'highestThreatKingdom' => $highestThreatKingdom,
        ]);
    }

};
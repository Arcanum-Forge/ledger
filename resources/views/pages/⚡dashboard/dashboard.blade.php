<div>
    {{-- Header --}}

    <x-dashboard.header eyebrow="Overview" title="Dashboard" />

    @island(name:'overview', lazy: true)

    @placeholder
        <x-dashboard.stats-card-placeholder :count="10" />
    @endplaceholder

        <x-dashboard.stats-card :stats="$this->stats" />

    @endisland


    @island(name:'threat-spotlight', lazy: true)

    @placeholder
        <x-dashboard.threat-spotlight-placeholder />
    @endplaceholder

        <x-dashboard.threat-spotlight :kingdom="$this->highestThreatKingdom" />
    @endisland

    {{-- Highlight panels --}}
    <div class="grid gap-6 lg:grid-cols-2">

        @island(lazy: true)

            @placeholder
                <x-dashboard.panel-placeholder />
            @endplaceholder

            <x-dashboard.panel title="Active Threat Reports" href="/threat-reports">
                @forelse ($this->activeThreats as $report)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            <p class="truncate font-serif text-sm text-parchment">
                                {{ $report->title }}
                            </p>
                            <p class="mt-0.5 text-[10px] text-faint">
                                {{ $report->report_number }}
                                · {{ $report->region->name ?? '—' }}
                                @if ($report->kingdom)
                                    · {{ $report->kingdom->name }}
                                @endif
                            </p>
                        </div>
                        <span class="shrink-0 text-[9px] uppercase tracking-[0.15em] {{ $report->level_color }}">
                            {{ $report->level }}
                        </span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-xs text-faint">
                        No active or investigating reports.
                    </p>
                @endforelse
            </x-dashboard.panel>
        @endisland



        @island(lazy: true)

            @placeholder
                <x-dashboard.panel-placeholder />
            @endplaceholder

            <x-dashboard.panel title="Most Dangerous Monsters" href="/monsters">
                @forelse ($this->dangerousMonsters as $monster)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            <p class="truncate font-serif text-sm text-parchment">
                                {{ $monster->name }}
                            </p>

                            <p class="mt-0.5 text-[10px] text-faint">
                                {{ $monster->classification }}
                                · {{ $monster->kingdom->name ?? 'Unconfirmed' }}
                            </p>
                        </div>
                        <span class="shrink-0 text-[9px] uppercase tracking-[0.15em] {{ $monster->threat_color }}">
                            {{ $monster->threat }}
                        </span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-xs text-faint">
                        No sightings recorded.
                    </p>
                @endforelse
            </x-dashboard.panel>
        @endisland

        @island(lazy: true)

            @placeholder
                <x-dashboard.panel-placeholder />
            @endplaceholder

            <x-dashboard.panel title="Recent Chronicle Entries" href="/records">
    
                @forelse ($this->recentRecords as $record)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            <p class="truncate font-serif text-sm text-parchment">{{ $record->title }}</p>
                            <p class="mt-0.5 text-[10px] text-faint">
                                {{ $record->category }} · {{ $record->author->name ?? 'Unknown' }}
                            </p>
                        </div>
                        @if ($record->confidential)
                            <span class="shrink-0 text-[9px] uppercase tracking-[0.15em] text-danger">Confidential</span>
                        @endif
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-xs text-faint">No records archived.</p>
                @endforelse
            </x-dashboard.panel>
        @endisland



        @island(lazy: true)

            @placeholder
                <x-dashboard.panel-placeholder />
            @endplaceholder

            <x-dashboard.panel title="Rising Factions" href="/factions">

                @forelse ($this->risingFactions as $faction)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            <p class="truncate font-serif text-sm text-parchment">{{ $faction->name }}</p>
                            <p class="mt-0.5 text-[10px] text-faint">
                                {{ $faction->kingdom->name ?? 'Unknown' }} · Led by
                                {{ $faction->leader->name ?? 'Unknown' }}
                            </p>
                        </div>
                        <span class="shrink-0 font-serif text-sm font-semibold text-parchment">{{ $faction->influence }}</span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-xs text-faint">No factions recorded.</p>
                @endforelse
            </x-dashboard.panel>
        @endisland
    </div>
</div>
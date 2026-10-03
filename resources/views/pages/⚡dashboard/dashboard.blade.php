<div>
    {{-- Header --}}
    <div class="mb-8">
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#806337]">The Realm</p>
        <h1 class="mt-1 font-serif text-3xl text-[#e8dfca]">Dashboard</h1>
    </div>

    {{-- Stat cards --}}
    <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
        @foreach ($stats as $stat)
            <a href="{{ $stat['href'] }}" wire:navigate
                class="border border-[#2c2922] bg-[#151310] p-5 transition hover:border-[#806337]/40 hover:bg-[#191611]">
                <p class="text-[9px] uppercase tracking-[0.2em] text-[#806337]">{{ $stat['label'] }}</p>
                <p class="mt-2 font-serif text-3xl text-[#d8c8a8]">{{ $stat['count'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Highest-threat kingdom spotlight --}}
    @if ($highestThreatKingdom)
        <div class="mb-8 border border-[#2c2922] bg-[#151310] p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-[9px] uppercase tracking-[0.25em] text-[#806337]">Highest Threat Kingdom</p>
                    <p class="mt-2 font-serif text-2xl text-[#e8dfca]">{{ $highestThreatKingdom->name }}</p>
                    <p class="mt-1 text-xs text-[#8f826b]">
                        {{ $highestThreatKingdom->title }} · Ruled by {{ $highestThreatKingdom->ruler->full_title ?? 'no one' }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] uppercase tracking-[0.2em] text-[#625744]">Threat Level</p>
                    <p class="mt-1 font-serif text-4xl {{ $highestThreatKingdom->threat_color ?? 'text-[#c14545]' }}">
                        {{ $highestThreatKingdom->threat }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Highlight panels --}}
    <div class="grid gap-6 lg:grid-cols-2">

        {{-- Active Threats --}}
        <div class="border border-[#2c2922] bg-[#151310]">
            <div class="flex items-center justify-between border-b border-[#2c2922] px-5 py-4">
                <p class="text-[9px] uppercase tracking-[0.25em] text-[#806337]">Active Threat Reports</p>
                <a href="/threat-reports" wire:navigate class="text-[9px] uppercase tracking-[0.15em] text-[#857861] hover:text-[#c59b4a]">View all →</a>
            </div>
            <div class="divide-y divide-[#2c2922]/60">
                @forelse ($activeThreats as $report)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            <p class="truncate font-serif text-sm text-[#ddd2bb]">{{ $report->title }}</p>
                            <p class="mt-0.5 text-[10px] text-[#625744]">
                                {{ $report->report_number }} · {{ $report->region->name ?? '—' }}
                                @if ($report->kingdom) · {{ $report->kingdom->name }} @endif
                            </p>
                        </div>
                        <span class="shrink-0 text-[9px] uppercase tracking-[0.15em]
                            {{ match ($report->level) {
                                'Critical' => 'text-[#c14545]',
                                'Severe' => 'text-[#b98967]',
                                default => 'text-[#c59b4a]',
                            } }}">
                            {{ $report->level }}
                        </span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-xs text-[#625744]">No active or investigating reports.</p>
                @endforelse
            </div>
        </div>

        {{-- Most Dangerous Monsters --}}
        <div class="border border-[#2c2922] bg-[#151310]">
            <div class="flex items-center justify-between border-b border-[#2c2922] px-5 py-4">
                <p class="text-[9px] uppercase tracking-[0.25em] text-[#806337]">Most Dangerous Monsters</p>
                <a href="/monsters" wire:navigate class="text-[9px] uppercase tracking-[0.15em] text-[#857861] hover:text-[#c59b4a]">View all →</a>
            </div>
            <div class="divide-y divide-[#2c2922]/60">
                @forelse ($dangerousMonsters as $monster)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            <p class="truncate font-serif text-sm text-[#ddd2bb]">{{ $monster->name }}</p>
                            <p class="mt-0.5 text-[10px] text-[#625744]">
                                {{ $monster->classification }} · {{ $monster->kingdom->name ?? 'Unconfirmed' }}
                            </p>
                        </div>
                        <span class="shrink-0 text-[9px] uppercase tracking-[0.15em]
                            {{ match ($monster->threat) {
                                'Extreme' => 'text-[#c14545]',
                                'High' => 'text-[#b98967]',
                                'Moderate' => 'text-[#c59b4a]',
                                default => 'text-[#8f826b]',
                            } }}">
                            {{ $monster->threat }}
                        </span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-xs text-[#625744]">No sightings recorded.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Chronicle Entries --}}
        <div class="border border-[#2c2922] bg-[#151310]">
            <div class="flex items-center justify-between border-b border-[#2c2922] px-5 py-4">
                <p class="text-[9px] uppercase tracking-[0.25em] text-[#806337]">Recent Chronicle Entries</p>
                <a href="/records" wire:navigate class="text-[9px] uppercase tracking-[0.15em] text-[#857861] hover:text-[#c59b4a]">View all →</a>
            </div>
            <div class="divide-y divide-[#2c2922]/60">
                @forelse ($recentRecords as $record)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            <p class="truncate font-serif text-sm text-[#ddd2bb]">{{ $record->title }}</p>
                            <p class="mt-0.5 text-[10px] text-[#625744]">
                                {{ $record->category }} · {{ $record->author->name ?? 'Unknown' }}
                            </p>
                        </div>
                        @if ($record->confidential)
                            <span class="shrink-0 text-[9px] uppercase tracking-[0.15em] text-[#c14545]">Confidential</span>
                        @endif
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-xs text-[#625744]">No records archived.</p>
                @endforelse
            </div>
        </div>

        {{-- Rising Factions --}}
        <div class="border border-[#2c2922] bg-[#151310]">
            <div class="flex items-center justify-between border-b border-[#2c2922] px-5 py-4">
                <p class="text-[9px] uppercase tracking-[0.25em] text-[#806337]">Rising Factions</p>
                <a href="/factions" wire:navigate class="text-[9px] uppercase tracking-[0.15em] text-[#857861] hover:text-[#c59b4a]">View all →</a>
            </div>
            <div class="divide-y divide-[#2c2922]/60">
                @forelse ($risingFactions as $faction)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            <p class="truncate font-serif text-sm text-[#ddd2bb]">{{ $faction->name }}</p>
                            <p class="mt-0.5 text-[10px] text-[#625744]">
                                {{ $faction->kingdom->name ?? 'Unknown' }} · Led by {{ $faction->leader->name ?? 'Unknown' }}
                            </p>
                        </div>
                        <span class="shrink-0 font-serif text-sm font-semibold text-[#d8c8a8]">{{ $faction->influence }}</span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-xs text-[#625744]">No factions recorded.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
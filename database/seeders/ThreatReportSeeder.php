<?php

namespace Database\Seeders;

use App\Models\Kingdom;
use App\Models\Region;
use App\Models\ThreatReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ThreatReportSeeder extends Seeder
{
    public function run(): void
    {
        // Kingdoms are already fully seeded (KingdomSeeder), so this is a
        // plain lookup — never a create.
        $kingdomId = fn(string $name): ?int => Kingdom::where('name', $name)->value('id');

        // These regions ("Northern Drakmor", "Ravenwatch Tower", etc.) are
        // finer-grained locations than the four broad regions RegionSeeder
        // creates, so they're new Region rows in their own right — created
        // here the first time a report mentions them.
        $regionId = fn(string $name): int => Region::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name],
        )->id;

        $reports = [
            [
                'slug' => 'ashen-host-movement',
                'report_number' => 'TR-0841',
                'title' => 'Ashen Host Movement',
                'region' => 'Northern Drakmor',
                'kingdom' => 'Drakmor',
                'type' => 'Military',
                'level' => 'Critical',
                'status' => 'Active',
                'sightings' => 17,
                'description' => 'Multiple warbands bearing the Ashen Covenant insignia have been observed moving toward the northern passes.',
            ],
            [
                'slug' => 'blackfang-pack-sighted',
                'report_number' => 'TR-0838',
                'title' => 'Blackfang Pack Sighted',
                'region' => 'Wyrmwood Frontier',
                'kingdom' => 'Elaria',
                'type' => 'Monster',
                'level' => 'Severe',
                'status' => 'Active',
                'sightings' => 9,
                'description' => 'A large pack of Blackfang wolves has descended from the northern forests. Three villages have reported livestock losses.',
            ],
            [
                'slug' => 'uknown-figure-at-ravenwatch',
                'report_number' => 'TR-0834',
                'title' => 'Unknown Figure at Ravenwatch',
                'region' => 'Ravenwatch Tower',
                'kingdom' => 'Auren',
                'type' => 'Unknown',
                'level' => 'Elevated',
                'status' => 'Investigating',
                'sightings' => 4,
                'description' => 'Watchmen report a cloaked individual appearing near the abandoned tower after nightfall. Identity remains unknown.',
            ],
            [
                'slug' => 'graveborn-activity',
                'report_number' => 'TR-0829',
                'title' => 'Graveborn Activity',
                'region' => "Old King's Road",
                'kingdom' => 'Valedorn',
                'type' => 'Undead',
                'level' => 'Severe',
                'status' => 'Contained',
                'sightings' => 12,
                'description' => 'Travelers reported undead activity along the eastern road. A patrol from the Iron Legion has secured the affected area.',
            ],
            [
                'slug' => 'merchant-caravan-dissapearance',
                'report_number' => 'TR-0821',
                'title' => 'Merchant Caravan Disappearance',
                'region' => 'Golden Fields',
                'kingdom' => 'Auren',
                'type' => 'Criminal',
                'level' => 'Elevated',
                'status' => 'Investigating',
                'sightings' => 3,
                'description' => 'Two merchant caravans have failed to arrive at their destinations. Evidence suggests organized ambushes.',
            ],
            [
                'slug' => 'dragonfire-in-the-east',
                'report_number' => 'TR-0816',
                'title' => 'Dragonfire in the East',
                'region' => 'Ashen Peaks',
                'kingdom' => 'Drakmor',
                'type' => 'Dragon',
                'level' => 'Critical',
                'status' => 'Unconfirmed',
                'sightings' => 1,
                'description' => 'A distant column of flame was witnessed above the Ashen Peaks. No physical evidence has yet been recovered.',
            ],
        ];

        foreach ($reports as $report) {
            ThreatReport::create([
                'slug' => $report['slug'],
                'report_number' => $report['report_number'],
                'title' => $report['title'],
                'region_id' => $regionId($report['region']),
                'kingdom_id' => $kingdomId($report['kingdom']),
                'type' => $report['type'],
                'level' => $report['level'],
                'status' => $report['status'],
                'sightings' => $report['sightings'],
                'description' => $report['description'],
            ]);
        }
    }
}
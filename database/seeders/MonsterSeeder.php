<?php

namespace Database\Seeders;

use App\Models\Kingdom;
use App\Models\Monster;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MonsterSeeder extends Seeder
{
    public function run(): void
    {
        // Looks up a Kingdom by name, same pattern as FactionSeeder. Names
        // that don't match an actual Kingdom — like the Veil Stalker's
        // "Unconfirmed" — resolve to null on their own, which is exactly
        // the right value for a sighting with no confirmed origin.
        $kingdomId = fn(string $name): ?int => Kingdom::where('name', $name)->value('id');

        $monsters = [
            [
                'name' => 'Blackfang Wolf',
                'classification' => 'Beast',
                'habitat' => 'Forest',
                'kingdom' => 'Elaria',
                'threat' => 'Moderate',
                'sightings' => 47,
                'status' => 'Common',
                'description' => 'A large predatory wolf with dark fur and unusually intelligent hunting behavior. Packs have been known to coordinate against armed patrols.',
            ],
            [
                'name' => 'Ash Drake',
                'classification' => 'Dragonkin',
                'habitat' => 'Mountains',
                'kingdom' => 'Drakmor',
                'threat' => 'High',
                'sightings' => 13,
                'status' => 'Rare',
                'description' => 'A lesser dragon bred by the volcanic ranges of Drakmor. Its breath carries burning ash capable of blinding targets and igniting dry terrain.',
            ],
            [
                'name' => 'Graveborn',
                'classification' => 'Undead',
                'habitat' => 'Ruins',
                'kingdom' => 'Valedorn',
                'threat' => 'High',
                'sightings' => 29,
                'status' => 'Active',
                'description' => 'Restless dead found near battlefields and forgotten burial grounds. Graveborn appear to retain fragments of their former combat instincts.',
            ],
            [
                'name' => 'Mire Hag',
                'classification' => 'Aberration',
                'habitat' => 'Swamp',
                'kingdom' => 'Elaria',
                'threat' => 'High',
                'sightings' => 8,
                'status' => 'Rare',
                'description' => 'A solitary swamp-dweller known to mimic the voices of travelers and lost children. Several disappearances have been attributed to its hunting grounds.',
            ],
            [
                'name' => 'Ironhide Boar',
                'classification' => 'Beast',
                'habitat' => 'Woodland',
                'kingdom' => 'Auren',
                'threat' => 'Moderate',
                'sightings' => 64,
                'status' => 'Common',
                'description' => 'An enormous boar whose hide becomes almost metallic with age. Territorial adults are capable of overturning carts and breaking wooden gates.',
            ],
            [
                'name' => 'Veil Stalker',
                'classification' => 'Unknown',
                'habitat' => 'Unknown',
                'kingdom' => 'Unconfirmed',
                'threat' => 'Extreme',
                'sightings' => 3,
                'status' => 'Unconfirmed',
                'description' => 'Little is known of this creature. Witnesses describe a tall figure appearing at the edge of torchlight before disappearing without a trace.',
            ],
            [
                'name' => 'Stoneback Troll',
                'classification' => 'Giant',
                'habitat' => 'Mountains',
                'kingdom' => 'Drakmor',
                'threat' => 'High',
                'sightings' => 21,
                'status' => 'Territorial',
                'description' => 'A heavily built mountain troll covered in mineral deposits. Their regenerative abilities make conventional weapons largely ineffective.',
            ],
            [
                'name' => 'River Serpent',
                'classification' => 'Beast',
                'habitat' => 'River',
                'kingdom' => 'Auren',
                'threat' => 'Low',
                'sightings' => 112,
                'status' => 'Common',
                'description' => 'Large aquatic predators found throughout the eastern waterways. Usually avoid settlements unless disturbed or deprived of prey.',
            ],
        ];

        foreach ($monsters as $monster) {
            Monster::create([
                'slug' => Str::slug($monster['name']),
                'name' => $monster['name'],
                'classification' => $monster['classification'],
                'habitat' => $monster['habitat'],
                'kingdom_id' => $kingdomId($monster['kingdom']),
                'threat' => $monster['threat'],
                'sightings' => $monster['sightings'],
                'status' => $monster['status'],
                'description' => $monster['description'],
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            [
                'name' => 'Western Realms',
                'description' => 'A prosperous collection of fertile kingdoms, ancient roads and fortified cities. The Western Realms are considered the heartland of civilized society and the center of commerce, diplomacy and royal influence.',
            ],
            [
                'name' => 'Eastern Marches',
                'description' => 'A dangerous frontier of mountains, fortresses and contested passes. The Eastern Marches serve as the first line of defense against monsters and hostile forces emerging from the wild.',
            ],
            [
                'name' => 'Emerald Wilds',
                'description' => 'An immense ancient woodland where towering trees cover the land and old magic still lingers beneath the roots. Elven courts, druids and mysterious forest spirits hold considerable influence here.',
            ],
            [
                'name' => 'Ashen Wastes',
                'description' => 'A desolate volcanic territory covered in blackened plains, poisonous fumes and rivers of molten stone. Ancient ruins buried beneath the ash hint at civilizations that existed long before the current kingdoms.',
            ],
            [
                'name' => 'Silver Coast',
                'description' => 'A wealthy coastal region known for silver mines, shipyards and thriving maritime trade. Its port cities attract merchants, adventurers and diplomats from nearly every major kingdom.',
            ],
            [
                'name' => 'Northern Wilds',
                'description' => 'A rugged wilderness of dense forests, rocky valleys and isolated settlements. The region remains largely untamed, with ancient beasts and forgotten ruins scattered throughout its interior.',
            ],
            [
                'name' => 'Ironspine Mountains',
                'description' => 'A colossal mountain range rich in iron, mithril and other rare minerals. Deep beneath the peaks lie enormous dwarven halls connected by tunnels that stretch for hundreds of miles.',
            ],
            [
                'name' => 'Drowned Fen',
                'description' => 'A vast marshland of blackwater rivers, sinking ruins and mist-covered islands. Travel is difficult and dangerous, and many ancient structures have slowly disappeared beneath the swamp.',
            ],
            [
                'name' => 'Southern Deserts',
                'description' => 'A vast expanse of scorching dunes, ancient ruins and oasis cities. Despite its harsh climate, the region is crossed by important trade routes connecting distant civilizations.',
            ],
            [
                'name' => 'Central Coast',
                'description' => 'A densely populated coastal region filled with independent cities, merchant houses and busy harbors. Wealth and political influence are often controlled by powerful guilds rather than traditional monarchs.',
            ],
            [
                'name' => 'Frostbound North',
                'description' => 'A frozen land of glaciers, snow-covered mountains and long winters. Its people have developed a resilient culture centered around hunting, warfare and survival in extreme conditions.',
            ],
            [
                'name' => 'Moonwood',
                'description' => 'A mysterious forest where moonlight seems to linger even during the darkest nights. The woodland is associated with celestial magic, strange spirits and ancient elven traditions.',
            ],
            [
                'name' => 'Cursed Frontier',
                'description' => 'A scarred borderland surrounding territories affected by an ancient magical catastrophe. Ruined settlements and abandoned fortresses remain under constant threat from monsters and supernatural phenomena.',
            ],
            [
                'name' => 'Sapphire Desert',
                'description' => 'A brilliant desert scattered with deposits of naturally occurring blue crystals. Its wealth has made the region an important center of trade, magical craftsmanship and luxury goods.',
            ],
            [
                'name' => 'Verdant Plains',
                'description' => 'A fertile agricultural region of rolling hills, vineyards and vast fields. Numerous roads connect its prosperous towns and villages to the great cities of the surrounding kingdoms.',
            ],
            [
                'name' => 'Storm Sea',
                'description' => 'A treacherous ocean notorious for violent storms, enormous waves and mysterious creatures. Despite the dangers, its islands contain valuable ports and settlements that control important sea routes.',
            ],
            [
                'name' => 'Great Steppe',
                'description' => 'An enormous grassland stretching beyond the horizon. Nomadic clans travel across its open plains with their herds, gathering under powerful warlords whenever the region faces a common threat.',
            ],
            [
                'name' => 'Sacred Vale',
                'description' => 'A peaceful valley surrounding ancient temples and holy sites. Pilgrims from across the known world travel here to visit its sacred shrines and seek the guidance of its religious orders.',
            ],
            [
                'name' => 'Veiled Marshes',
                'description' => 'A region permanently shrouded in thick mist where visibility can fall to only a few paces. Travelers tell stories of strange lights, forgotten ruins and voices that echo across the water.',
            ],
            [
                'name' => 'Aetherial Highlands',
                'description' => 'A mountainous region saturated with arcane energy. Floating islands, magical anomalies and ancient towers can be found throughout the highlands, making the region a center of magical study.',
            ],
            [
                'name' => 'Broken Lands',
                'description' => 'A fractured territory left behind after the collapse of an ancient empire. Ruined cities, abandoned battlefields and competing noble houses dominate the landscape as factions struggle for control.',
            ],
        ];

        foreach ($regions as $data) {
            Region::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                ]
            );
        }
    }
}
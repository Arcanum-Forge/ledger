<?php

namespace Database\Seeders;

use App\Models\Kingdom;
use App\Models\Region;
use App\Models\Ruler;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KingdomSeeder extends Seeder
{
    public function run(): void
    {
        $kingdoms = [
            [
                'slug' => 'auren',
                'name' => 'Auren',
                'title' => 'The Golden Kingdom',
                'description' => 'A prosperous kingdom of knights, merchant houses and towering cathedrals. Auren considers itself the last bastion of civilization.',
                'population' => '2.8 million',
                'alignment' => 'Lawful',
                'threat' => 32,
                'region' => 'Western Realms',
                'founded' => 'Year 184',
                'ruler' => [
                    'honorific' => 'King',
                    'name' => 'Aldemar IV',
                    'bio' => 'Aldemar IV is a seasoned monarch known for his measured diplomacy and devotion to Auren. After ascending the throne during a period of economic uncertainty, he strengthened the kingdom’s merchant houses and expanded the royal knightly orders. Though regarded as a cautious ruler, Aldemar is known to personally oversee matters concerning the security of Auren’s borders.',
                ],
            ],

            [
                'slug' => 'valedorn',
                'name' => 'Valedorn',
                'title' => 'The Iron Marches',
                'description' => 'A militarized frontier realm forged by centuries of war. Its fortresses guard the eastern passes against creatures from the wild.',
                'population' => '1.6 million',
                'alignment' => 'Neutral',
                'threat' => 67,
                'region' => 'Eastern Marches',
                'founded' => 'Year 91',
                'ruler' => [
                    'honorific' => 'Queen',
                    'name' => 'Maerwyn II',
                    'bio' => 'Maerwyn II inherited a kingdom surrounded by enemies and transformed Valedorn into one of the most heavily fortified realms in the east. A disciplined commander and pragmatic ruler, she spends much of her reign preparing for threats beyond the frontier while maintaining uneasy relations with neighboring kingdoms.',

                ],
            ],

            [
                'slug' => 'elaria',
                'name' => 'Elaria',
                'title' => 'The Emerald Realm',
                'description' => 'An ancient woodland kingdom where elven courts, druids and old magic still hold influence over the land.',
                'population' => '940 thousand',
                'alignment' => 'Good',
                'threat' => 18,
                'region' => 'Emerald Wilds',
                'founded' => 'Year 12',
                'ruler' => [
                    'honorific' => 'High Queen',
                    'name' => 'Seraphine',
                    'bio' => 'Seraphine has ruled Elaria for generations and is considered one of the oldest living members of the Emerald Court. She is deeply connected to the ancient forests and is known for her ability to negotiate with both mortal rulers and the spirits that inhabit the wilds.',
                ],
            ],

            [
                'slug' => 'drakmor',
                'name' => 'Drakmor',
                'title' => 'The Ashen Dominion',
                'description' => 'A harsh volcanic dominion ruled from a fortress of black stone. Rumors speak of forbidden rituals beneath its capital.',
                'population' => '730 thousand',
                'alignment' => 'Chaotic',
                'threat' => 89,
                'region' => 'Ashen Wastes',
                'founded' => 'Year 307',
                'ruler' => [
                    'honorific' => 'Lord',
                    'name' => 'Varkhan',
                    'bio' => 'Varkhan seized control of Drakmor after a brutal succession struggle and has ruled the Ashen Dominion through fear and absolute authority ever since. Little is known about his origins, though rumors claim he studied forbidden magic beneath the volcanic capital.',

                ],
            ],

            [
                'slug' => 'caelwyn',
                'name' => 'Caelwyn',
                'title' => 'The Silver Principality',
                'description' => 'A wealthy coastal principality renowned for its shipwrights, astronomers and silver mines. Caelwyn maintains neutrality through trade and diplomacy.',
                'population' => '1.2 million',
                'alignment' => 'Neutral',
                'threat' => 24,
                'region' => 'Silver Coast',
                'founded' => 'Year 226',
                'ruler' => [
                    'honorific' => 'Prince',
                    'name' => 'Edric Valemont',
                    'bio' => 'Edric Valemont is a charismatic prince who has turned Caelwyn into one of the western seas’ most influential trading powers. He is celebrated for his diplomatic skill and fascination with astronomy, often spending evenings studying the heavens from the observatory above his palace.',
                ],
            ],

            [
                'slug' => 'thornvale',
                'name' => 'Thornvale',
                'title' => 'The Briar Crown',
                'description' => 'A secluded kingdom surrounded by enormous enchanted thorn forests. Its people are fiercely protective of their borders and ancient traditions.',
                'population' => '510 thousand',
                'alignment' => 'Good',
                'threat' => 41,
                'region' => 'Northern Wilds',
                'founded' => 'Year 73',
                'ruler' => [
                    'honorific' => 'Queen',
                    'name' => 'Elowen Thornhart',
                    'bio' => 'Elowen Thornhart is the guardian of Thornvale’s ancient forests and one of the most respected rulers among the northern kingdoms. She maintains a close relationship with the druids and has forbidden large-scale logging within the enchanted thornwood.',
                ],
            ],

            [
                'slug' => 'karak-dur',
                'name' => 'Karak Dur',
                'title' => 'The Mountain Hold',
                'description' => 'A vast dwarven kingdom carved beneath the Ironspine Mountains. Its halls contain some of the oldest forges and richest veins of mithril in the known world.',
                'population' => '680 thousand',
                'alignment' => 'Lawful',
                'threat' => 38,
                'region' => 'Ironspine Mountains',
                'founded' => 'Year -402',
                'ruler' => [
                    'honorific' => 'High King',
                    'name' => 'Borin Stonehelm',
                    'bio' => 'Borin Stonehelm is a veteran dwarven king who spent much of his youth fighting beneath the Ironspine Mountains. Under his reign, Karak Dur has expanded its mining operations while restoring several ancient halls abandoned by previous generations.',

                ],
            ],

            [
                'slug' => 'mirehaven',
                'name' => 'Mirehaven',
                'title' => 'The Drowned Kingdom',
                'description' => 'A kingdom of marshes, blackwater rivers and half-submerged ruins. Its people have learned to survive among creatures that would destroy ordinary settlements.',
                'population' => '390 thousand',
                'alignment' => 'Neutral',
                'threat' => 72,
                'region' => 'Drowned Fen',
                'founded' => 'Year 158',
                'ruler' => [
                    'honorific' => 'Marsh Lord',
                    'name' => 'Odran Mireborn',
                    'bio' => 'Odran Mireborn was born in one of Mirehaven’s most isolated settlements and rose through the ranks of its marsh clans. He understands the waterways better than almost anyone alive and has repeatedly protected the kingdom from creatures emerging from the deepest swamps.',

                ],
            ],

            [
                'slug' => 'solmara',
                'name' => 'Solmara',
                'title' => 'The Sunlit Empire',
                'description' => 'A vast southern empire built around immense desert cities, ancient temples and sprawling trade routes connecting distant civilizations.',
                'population' => '4.7 million',
                'alignment' => 'Lawful',
                'threat' => 46,
                'region' => 'Southern Deserts',
                'founded' => 'Year 43',
                'ruler' => [
                    'honorific' => 'Emperor',
                    'name' => 'Cassian Aurelios',
                    'bio' => 'Cassian Aurelios commands one of the largest realms in the southern world. A sophisticated statesman and patron of architecture, he has funded enormous temples, roads and aqueducts while attempting to bring the rival desert provinces under stronger imperial control.',
                ],
            ],

            [
                'slug' => 'varanth',
                'name' => 'Varanth',
                'title' => 'The Free Cities',
                'description' => 'A loose alliance of wealthy city-states governed by merchant princes, guild councils and powerful banking families.',
                'population' => '2.1 million',
                'alignment' => 'Neutral',
                'threat' => 29,
                'region' => 'Central Coast',
                'founded' => 'Year 291',
                'ruler' => [
                    'honorific' => 'First Consul',
                    'name' => 'Lucian Merrow',
                    'bio' => 'Lucian Merrow rose from a prominent merchant family to become the elected First Consul of Varanth. His administration is heavily focused on trade, banking and maintaining the fragile balance between the powerful city-states that make up the alliance.',
                ],
            ],

            [
                'slug' => 'frostgard',
                'name' => 'Frostgard',
                'title' => 'The Frozen Crown',
                'description' => 'A northern kingdom of glaciers and endless winter. Its warriors are hardened by the cold and its settlements are built around enormous stone hearths.',
                'population' => '820 thousand',
                'alignment' => 'Lawful',
                'threat' => 61,
                'region' => 'Frostbound North',
                'founded' => 'Year 119',
                'ruler' => [
                    'honorific' => 'Jarl-King',
                    'name' => 'Hakon Frostbane',
                    'bio' => 'Hakon Frostbane earned his name during the Siege of Whitefang Pass, where he held a mountain fortress against an invading host through an entire winter. He now rules Frostgard as both king and war leader, demanding discipline and self-reliance from his people.',
                ],
            ],

            [
                'slug' => 'lyrathis',
                'name' => 'Lyrathis',
                'title' => 'The Moonlit Court',
                'description' => 'An enigmatic realm hidden within forests that never see daylight. Its rulers are said to possess knowledge of forgotten celestial magic.',
                'population' => '270 thousand',
                'alignment' => 'Neutral',
                'threat' => 55,
                'region' => 'Moonwood',
                'founded' => 'Year -87',
                'ruler' => [
                    'honorific' => 'Moon Queen',
                    'name' => 'Nymeria Vael',
                    'bio' => 'Nymeria Vael is an enigmatic ruler whose origins are known only to the highest members of the Moonlit Court. She is said to possess extensive knowledge of celestial magic and rarely appears before foreign diplomats without her face concealed behind a silver veil.',
                ],
            ],

            [
                'slug' => 'grimholt',
                'name' => 'Grimholt',
                'title' => 'The Black Bastion',
                'description' => 'A fortress kingdom established to contain monsters emerging from the cursed lands beyond its walls. Almost every citizen receives military training.',
                'population' => '610 thousand',
                'alignment' => 'Lawful',
                'threat' => 78,
                'region' => 'Cursed Frontier',
                'founded' => 'Year 342',
                'ruler' => [
                    'honorific' => 'Warden-King',
                    'name' => 'Roderic Grim',
                    'bio' => 'Roderic Grim founded his reputation as a monster hunter before becoming ruler of Grimholt. He has devoted his reign to strengthening the Black Bastion and maintaining the frontier defenses against creatures emerging from the cursed lands.',
                ],
            ],

            [
                'slug' => 'azharra',
                'name' => 'Azharra',
                'title' => 'The Sapphire Sultanate',
                'description' => 'A prosperous desert sultanate whose wealth comes from spice routes, enchanted glassworks and vast deposits of blue crystal.',
                'population' => '1.9 million',
                'alignment' => 'Good',
                'threat' => 35,
                'region' => 'Sapphire Desert',
                'founded' => 'Year 201',
                'ruler' => [
                    'honorific' => 'Sultan',
                    'name' => 'Rahim al-Zahir',
                    'bio' => 'Rahim al-Zahir is a wealthy and influential sultan whose court is famous for its scholars, artisans and merchants. He has expanded Azharra’s influence through trade rather than conquest and maintains diplomatic relationships with kingdoms across the southern continent.',
                ],
            ],

            [
                'slug' => 'veloria',
                'name' => 'Veloria',
                'title' => 'The Rose Kingdom',
                'description' => 'A fertile kingdom famous for vineyards, rose gardens, grand tournaments and an elaborate court culture.',
                'population' => '1.4 million',
                'alignment' => 'Good',
                'threat' => 21,
                'region' => 'Verdant Plains',
                'founded' => 'Year 164',
                'ruler' => [
                    'honorific' => 'Queen',
                    'name' => 'Isolde III',
                    'bio' => 'Isolde III is a beloved queen whose reign has been associated with prosperity, festivals and cultural development. She is an enthusiastic patron of musicians, painters and knights, while secretly maintaining an extensive network of royal spies throughout neighboring courts.',
                ],
            ],

            [
                'slug' => 'kael-tor',
                'name' => 'Kael Tor',
                'title' => 'The Storm Isles',
                'description' => 'An island kingdom scattered across violent seas. Its sailors are renowned as navigators, pirates, privateers and monster hunters.',
                'population' => '560 thousand',
                'alignment' => 'Chaotic',
                'threat' => 64,
                'region' => 'Storm Sea',
                'founded' => 'Year 248',
                'ruler' => [
                    'honorific' => 'Sea King',
                    'name' => 'Darius Blacktide',
                    'bio' => 'Darius Blacktide began his life as a notorious pirate before uniting several rival island clans beneath his banner. Though feared by merchants and naval commanders, he has become an effective ruler who protects the Storm Isles from foreign fleets and sea monsters alike.',
                ],
            ],

            [
                'slug' => 'orduun',
                'name' => 'Orduun',
                'title' => 'The Endless Steppe',
                'description' => 'A nomadic confederation stretching across vast grasslands. Dozens of clans gather beneath a single banner during times of war.',
                'population' => '1.1 million',
                'alignment' => 'Neutral',
                'threat' => 58,
                'region' => 'Great Steppe',
                'founded' => 'Year 36',
                'ruler' => [
                    'honorific' => 'Great Khan',
                    'name' => 'Temur Arak',
                    'bio' => 'Temur Arak united the rival clans of the Great Steppe after years of warfare. A formidable mounted warrior and respected strategist, he rules through a combination of personal authority, clan alliances and traditional oaths of loyalty.',
                ],
            ],

            [
                'slug' => 'seradyn',
                'name' => 'Seradyn',
                'title' => 'The Holy Dominion',
                'description' => 'A deeply religious kingdom ruled by a sacred council. Pilgrims travel from across the continent to visit its legendary cathedral.',
                'population' => '1.7 million',
                'alignment' => 'Lawful',
                'threat' => 27,
                'region' => 'Sacred Vale',
                'founded' => 'Year 102',
                'ruler' => [
                    'honorific' => 'Divine Regent',
                    'name' => 'Aurelia Voss',
                    'bio' => 'Aurelia Voss was chosen by the Sacred Council after decades of service to Seradyn’s religious orders. She is regarded as a devoted guardian of the kingdom’s holy sites and has dedicated her reign to preserving the ancient traditions of the Sacred Vale.',
                ],
            ],

            [
                'slug' => 'morvane',
                'name' => 'Morvane',
                'title' => 'The Veiled Realm',
                'description' => 'A shadowed kingdom surrounded by perpetual mist. Foreign travelers rarely see its capital and fewer return willing to speak about it.',
                'population' => '440 thousand',
                'alignment' => 'Chaotic',
                'threat' => 84,
                'region' => 'Veiled Marshes',
                'founded' => 'Year 278',
                'ruler' => [
                    'honorific' => 'Veiled Queen',
                    'name' => 'Morgana Veyr',
                    'bio' => 'Almost nothing is known with certainty about Morgana Veyr. She appeared in the royal court shortly before the previous dynasty disappeared and has ruled Morvane from behind layers of secrecy ever since. Even her closest advisers claim to know little about her true intentions.',
                ],
            ],

            [
                'slug' => 'aetheris',
                'name' => 'Aetheris',
                'title' => 'The Arcane Kingdom',
                'description' => 'A realm dominated by magical academies, floating towers and ancient arcane laboratories. Its rulers are selected from the highest circles of magical scholarship.',
                'population' => '870 thousand',
                'alignment' => 'Neutral',
                'threat' => 52,
                'region' => 'Aetherial Highlands',
                'founded' => 'Year 389',
                'ruler' => [
                    'honorific' => 'Archmage',
                    'name' => 'Thaddeus Arcan',
                    'bio' => 'Thaddeus Arcan is one of the most accomplished magical scholars of his generation. Rather than inheriting Aetheris through bloodline, he earned his position through decades of arcane research and was elected by the kingdom’s Circle of Magisters.',
                ],
            ],

            [
                'slug' => 'dun-kareth',
                'name' => 'Dun Kareth',
                'title' => 'The Broken Crown',
                'description' => 'Once the heart of a mighty empire, Dun Kareth is now divided between rival noble houses competing for control of its ruined capital.',
                'population' => '920 thousand',
                'alignment' => 'Chaotic',
                'threat' => 76,
                'region' => 'Broken Lands',
                'founded' => 'Year 7',
                'ruler' => [
                    'honorific' => 'Lord Protector',
                    'name' => 'Garran Kareth',
                    'bio' => 'Garran Kareth claims descent from the ancient royal dynasty that once ruled the Broken Lands. He currently controls the ruined capital and spends much of his reign negotiating with rival noble houses while attempting to restore the former kingdom to its old glory.',
                ],
            ],
        ];

        foreach ($kingdoms as $data) {
            $region = Region::where('name', $data['region'])->firstOrFail();

            $ruler = Ruler::firstOrCreate(
                [
                    'name' => $data['ruler']['name'],
                ],
                [
                    'slug' => Str::slug($data['ruler']['name']),
                    'honorific' => $data['ruler']['honorific'],
                    'bio' => $data['ruler']['bio'],
                ]
            );

            Kingdom::updateOrCreate(
                [
                    'slug' => $data['slug'],
                ],
                [
                    'name' => $data['name'],
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'region_id' => $region->id,
                    'ruler_id' => $ruler->id,
                    'population' => $data['population'],
                    'alignment' => $data['alignment'],
                    'threat' => $data['threat'],
                    'founded' => $data['founded'],
                ]
            );
        }
    }
}